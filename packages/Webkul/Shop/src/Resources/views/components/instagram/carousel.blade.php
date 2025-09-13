@props(['items' => []])

@php
    try {
        $token = env('INSTAGRAM_TOKEN');
        $id    = env('INSTAGRAM_ID');
        $url   = env('INSTAGRAM_URL', "https://graph.instagram.com/{$id}/media");
        if ($token && $id) {
            $response = Http::timeout(6)
            ->withOptions(['verify' => false])
            ->get($url, [
                'access_token' => $token,
                'fields'       => 'id,media_type,media_url,permalink,thumbnail_url,caption',
                'limit'        => 48
            ]);
            if ($response->ok()) {
                $payload = $response->json();
                $medias  = $payload['data'] ?? [];
                $items = array_values(array_filter(array_map(function ($media) {
                    $mediaType = strtolower($media['media_type'] ?? 'image');
                    $type      = $mediaType === 'video' ? 'video' : 'image';
                    $src       = $media['media_url'] ?? ($media['thumbnail_url'] ?? null);
                    if (empty($src)) {
                        return null;
                    }
                    $caption = $media['caption'] ?? 'Instagram';
                    if (function_exists('mb_strimwidth')) {
                        $caption = mb_strimwidth($caption, 0, 120, '…', 'UTF-8');
                    }
                    return [
                        'type' => $type,
                        'src'  => $src,
                        'url'  => $media['permalink'] ?? '#',
                        'alt'  => $caption,
                    ];
                }, $medias)));

                if (empty($items)) {
                    \Illuminate\Support\Facades\Log::info('Instagram API sin resultados', [
                        'endpoint' => $url,
                        'count'    => 0,
                    ]);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Instagram API respuesta no OK', [
                    'status'   => $response->status(),
                    'endpoint' => $url,
                    'body'     => $response->body(),
                ]);
            }
        } else {
            \Illuminate\Support\Facades\Log::warning('Instagram credenciales faltantes', [
                'has_token' => (bool) $token,
                'has_id'    => (bool) $id,
            ]);
        }
    } catch (\Throwable $e) {
        $items = $items ?? [];
        \Illuminate\Support\Facades\Log::error('Instagram API excepción', [
            'message' => $e->getMessage(),
        ]);
    }
@endphp

<v-instagram-carousel :items='@json($items)'></v-instagram-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-instagram-carousel-template"
    >
        <div class="container mt-20 max-lg:px-8 max-md:mt-8 max-sm:mt-7 max-sm:!px-4">
            <div class="flex justify-between">
                <h3 class="font-semibold text-brandNavy text-3xl max-md:text-2xl max-sm:text-xl">@{{ heading }}</h3>
            </div>
            <div v-if="!items || items.length === 0" class="mt-8 text-center text-brandNavy/70">
                <p>No hay publicaciones disponibles en este momento.</p>
            </div>

            <div
                v-else
                ref="swiperContainer"
                class="flex gap-3 pb-2.5 mt-10 overflow-auto scroll-smooth scrollbar-hide snap-x snap-mandatory max-md:gap-2 max-sm:gap-2 max-md:mt-4 max-md:pb-0 max-md:whitespace-nowrap"
            >
                <div
                    v-for="(item, index) in items"
                    :key="index"
                    class="relative flex-none w-full sm:w-[calc((100%-12px)/2)] md:w-[calc((100%-24px)/3)] lg:w-[calc((100%-36px)/4)] h-96 lg:h-[32rem] rounded-md overflow-hidden snap-start"
                >
                    <template v-if="item.type === 'image'">
                        <img
                            class="w-full h-full object-cover"
                            :src="item.src"
                            :alt="item.alt || 'Instagram imagen'"
                            loading="lazy"
                        />
                    </template>
                    <template v-if="item.type === 'video'">
                        <video
                            autoplay
                            loop
                            muted
                            playsinline
                            class="w-full h-full object-cover"
                        >
                            <source :src="item.src" type="video/mp4" />
                        </video>
                    </template>
                    <a
                        :href="item.url"
                        target="_blank"
                        rel="noopener"
                        class="group absolute w-full h-full top-0 left-0 bg-black/0 hover:bg-black/70 transition-colors duration-300"
                        aria-label="Ver en Instagram"
                    >
                        <svg
                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                            fill="#ffffff"
                        >
                            <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3z"/>
                            <path d="M12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6z"/>
                            <circle cx="17.5" cy="6.5" r="1.5"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-instagram-carousel', {
            template: '#v-instagram-carousel-template',
            props: {
                items: {
                    type: Array,
                    default: () => [],
                },
                heading: {
                    type: String,
                    default: 'Síguenos en Instagram',
                },
            },
            data() {
                return {
                    offset: 0,
                    autoPlayInterval: null,
                    gap: 0,
                };
            },
            mounted() {
                this.updateOffset();
                this.play();
                window.addEventListener('resize', this.updateOffset);
            },
            beforeDestroy() {
                clearInterval(this.autoPlayInterval);
                window.removeEventListener('resize', this.updateOffset);
            },
            methods: {
                updateOffset() {
                    const container = this.$refs.swiperContainer;
                    if (! container || ! container.children || ! container.children.length) {
                        return;
                    }
                    const firstChild = container.children[0];
                    const styles = getComputedStyle(container);
                    this.gap = parseFloat(styles.columnGap || styles.gap || '0');
                    this.offset = firstChild.getBoundingClientRect().width + this.gap;
                },
                itemsPerView() {
                    const width = window.innerWidth;
                    if (width >= 1024) return 4; // lg
                    if (width >= 768) return 3;  // md
                    return 2;                    // xs || sm
                },
                play() {
                    clearInterval(this.autoPlayInterval);
                    const container = this.$refs.swiperContainer;
                    if (! container) return;
                    this.autoPlayInterval = setInterval(() => {
                        const perView = this.itemsPerView();
                        const groupStep = Math.max(1, perView) * this.offset - this.gap;
                        if (container.scrollLeft + container.clientWidth + groupStep >= container.scrollWidth - 1) {
                            container.scrollLeft = 0;
                        } else {
                            container.scrollLeft += groupStep;
                        }
                    }, 5500);
                },
            },
        });
    </script>
@endPushOnce