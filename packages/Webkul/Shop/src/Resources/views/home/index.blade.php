@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{  $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>
    
    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php ($data = $customization->options) @endphp

        <!-- Static content -->
        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <!-- Image Carousel -->
                <x-shop::carousel
                    :options="$data"
                    aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
                />

                @break
            @case ($customization::STATIC_CONTENT)
                <!-- push style -->
                @if (! empty($data['css']))
                    @push ('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                <!-- render html -->
                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif

                @break
            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                <x-shop::categories.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.categories.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.home.index')"
                    aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                />

                @break
            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                <x-shop::products.carousel
                    :title="$data['title'] ?? ''"
                    :src="route('shop.api.products.index', $data['filters'] ?? [])"
                    :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                    aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                />

                @break
        @endswitch
    @endforeach

    <!-- Instagram Carousel (custom) -->
    @php
        $media = [
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-2.cdninstagram.com/o1/v/t2/f2/m86/AQPbJJSUVKR2E_KMyBIuNuHTgvfBYB79FoCjtj0-boVVHLWOWHNHJurdDcHspTGFEgSdWBTfUIs-B7sYfZZddQaxpUAqngFFKTgOcJI.mp4?_nc_cat=105&_nc_sid=5e9851&_nc_ht=scontent-lga3-2.cdninstagram.com&_nc_ohc=BkB6Z-YT-kwQ7kNvwEy1RC1&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjQ5NzM5MTg4Mzk3MzgxNywidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjIyLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=9d88c27ad5c8d1fd&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9DQjQ4NTM5MDVFNTAxNzM4QTA0NzA4M0Q4Q0M1QThCMF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTko3eEI3LWFMdVJtbVVFQURhc0tzNXFTWWdKYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8tLfwavX7wgVAigCQzMsF0A2iHKwIMScGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=-b10hHYebN1xS9IQpZPDYQ&_nc_zt=28&oh=00_AfXzBrgy7ZEksJ9IRUuMLo8K9vmLRNr3DZhc0HyKn9hUYg&oe=68AD2352', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-3.cdninstagram.com/o1/v/t2/f2/m86/AQPI58l7udusy3Sng5v_hYvoCTFgyDyPhQYU0L6x7jORglnaTZUlLxEpQKZrUeUxJRPUvvBuwgzLmjSMqIApKegOrLu4WUrSMre6_eA.mp4?_nc_cat=108&_nc_sid=5e9851&_nc_ht=scontent-lga3-3.cdninstagram.com&_nc_ohc=F72POe4FZJkQ7kNvwEaAoyN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTM1MDQ1MzA0OTM4NjA3NSwidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjEzLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=c88671d11ff86515&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9BQzRCQ0JGQkIzNzc3NjlGMTBDOEU3RDMzMjc3QzBCMl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR1FaZlI0YWt5NjczaVFFQUZxM3FJTWxOaTRYYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmtpHbpNuO5gQVAigCQzMsF0ArcKPXCj1xGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=pV6IKdV5koF9SdY2Z0x3GQ&_nc_zt=28&oh=00_AfUC37IejXnAhdd8bqzOy6U3NtYDCwSaJHITFpURCtnkNA&oe=68AD0FCD', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-2.cdninstagram.com/o1/v/t2/f2/m86/AQPbJJSUVKR2E_KMyBIuNuHTgvfBYB79FoCjtj0-boVVHLWOWHNHJurdDcHspTGFEgSdWBTfUIs-B7sYfZZddQaxpUAqngFFKTgOcJI.mp4?_nc_cat=105&_nc_sid=5e9851&_nc_ht=scontent-lga3-2.cdninstagram.com&_nc_ohc=BkB6Z-YT-kwQ7kNvwEy1RC1&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjQ5NzM5MTg4Mzk3MzgxNywidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjIyLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=9d88c27ad5c8d1fd&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9DQjQ4NTM5MDVFNTAxNzM4QTA0NzA4M0Q4Q0M1QThCMF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTko3eEI3LWFMdVJtbVVFQURhc0tzNXFTWWdKYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8tLfwavX7wgVAigCQzMsF0A2iHKwIMScGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=-b10hHYebN1xS9IQpZPDYQ&_nc_zt=28&oh=00_AfXzBrgy7ZEksJ9IRUuMLo8K9vmLRNr3DZhc0HyKn9hUYg&oe=68AD2352', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-3.cdninstagram.com/o1/v/t2/f2/m86/AQPI58l7udusy3Sng5v_hYvoCTFgyDyPhQYU0L6x7jORglnaTZUlLxEpQKZrUeUxJRPUvvBuwgzLmjSMqIApKegOrLu4WUrSMre6_eA.mp4?_nc_cat=108&_nc_sid=5e9851&_nc_ht=scontent-lga3-3.cdninstagram.com&_nc_ohc=F72POe4FZJkQ7kNvwEaAoyN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTM1MDQ1MzA0OTM4NjA3NSwidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjEzLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=c88671d11ff86515&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9BQzRCQ0JGQkIzNzc3NjlGMTBDOEU3RDMzMjc3QzBCMl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR1FaZlI0YWt5NjczaVFFQUZxM3FJTWxOaTRYYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmtpHbpNuO5gQVAigCQzMsF0ArcKPXCj1xGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=pV6IKdV5koF9SdY2Z0x3GQ&_nc_zt=28&oh=00_AfUC37IejXnAhdd8bqzOy6U3NtYDCwSaJHITFpURCtnkNA&oe=68AD0FCD', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-2.cdninstagram.com/o1/v/t2/f2/m86/AQPbJJSUVKR2E_KMyBIuNuHTgvfBYB79FoCjtj0-boVVHLWOWHNHJurdDcHspTGFEgSdWBTfUIs-B7sYfZZddQaxpUAqngFFKTgOcJI.mp4?_nc_cat=105&_nc_sid=5e9851&_nc_ht=scontent-lga3-2.cdninstagram.com&_nc_ohc=BkB6Z-YT-kwQ7kNvwEy1RC1&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjQ5NzM5MTg4Mzk3MzgxNywidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjIyLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=9d88c27ad5c8d1fd&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9DQjQ4NTM5MDVFNTAxNzM4QTA0NzA4M0Q4Q0M1QThCMF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTko3eEI3LWFMdVJtbVVFQURhc0tzNXFTWWdKYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8tLfwavX7wgVAigCQzMsF0A2iHKwIMScGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=-b10hHYebN1xS9IQpZPDYQ&_nc_zt=28&oh=00_AfXzBrgy7ZEksJ9IRUuMLo8K9vmLRNr3DZhc0HyKn9hUYg&oe=68AD2352', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-3.cdninstagram.com/o1/v/t2/f2/m86/AQPI58l7udusy3Sng5v_hYvoCTFgyDyPhQYU0L6x7jORglnaTZUlLxEpQKZrUeUxJRPUvvBuwgzLmjSMqIApKegOrLu4WUrSMre6_eA.mp4?_nc_cat=108&_nc_sid=5e9851&_nc_ht=scontent-lga3-3.cdninstagram.com&_nc_ohc=F72POe4FZJkQ7kNvwEaAoyN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTM1MDQ1MzA0OTM4NjA3NSwidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjEzLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=c88671d11ff86515&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9BQzRCQ0JGQkIzNzc3NjlGMTBDOEU3RDMzMjc3QzBCMl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR1FaZlI0YWt5NjczaVFFQUZxM3FJTWxOaTRYYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmtpHbpNuO5gQVAigCQzMsF0ArcKPXCj1xGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=pV6IKdV5koF9SdY2Z0x3GQ&_nc_zt=28&oh=00_AfUC37IejXnAhdd8bqzOy6U3NtYDCwSaJHITFpURCtnkNA&oe=68AD0FCD', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-2.cdninstagram.com/o1/v/t2/f2/m86/AQPbJJSUVKR2E_KMyBIuNuHTgvfBYB79FoCjtj0-boVVHLWOWHNHJurdDcHspTGFEgSdWBTfUIs-B7sYfZZddQaxpUAqngFFKTgOcJI.mp4?_nc_cat=105&_nc_sid=5e9851&_nc_ht=scontent-lga3-2.cdninstagram.com&_nc_ohc=BkB6Z-YT-kwQ7kNvwEy1RC1&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjQ5NzM5MTg4Mzk3MzgxNywidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjIyLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=9d88c27ad5c8d1fd&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9DQjQ4NTM5MDVFNTAxNzM4QTA0NzA4M0Q4Q0M1QThCMF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTko3eEI3LWFMdVJtbVVFQURhc0tzNXFTWWdKYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8tLfwavX7wgVAigCQzMsF0A2iHKwIMScGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=-b10hHYebN1xS9IQpZPDYQ&_nc_zt=28&oh=00_AfXzBrgy7ZEksJ9IRUuMLo8K9vmLRNr3DZhc0HyKn9hUYg&oe=68AD2352', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-3.cdninstagram.com/o1/v/t2/f2/m86/AQPI58l7udusy3Sng5v_hYvoCTFgyDyPhQYU0L6x7jORglnaTZUlLxEpQKZrUeUxJRPUvvBuwgzLmjSMqIApKegOrLu4WUrSMre6_eA.mp4?_nc_cat=108&_nc_sid=5e9851&_nc_ht=scontent-lga3-3.cdninstagram.com&_nc_ohc=F72POe4FZJkQ7kNvwEaAoyN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTM1MDQ1MzA0OTM4NjA3NSwidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjEzLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=c88671d11ff86515&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9BQzRCQ0JGQkIzNzc3NjlGMTBDOEU3RDMzMjc3QzBCMl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR1FaZlI0YWt5NjczaVFFQUZxM3FJTWxOaTRYYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmtpHbpNuO5gQVAigCQzMsF0ArcKPXCj1xGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=pV6IKdV5koF9SdY2Z0x3GQ&_nc_zt=28&oh=00_AfUC37IejXnAhdd8bqzOy6U3NtYDCwSaJHITFpURCtnkNA&oe=68AD0FCD', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-2.cdninstagram.com/o1/v/t2/f2/m86/AQPbJJSUVKR2E_KMyBIuNuHTgvfBYB79FoCjtj0-boVVHLWOWHNHJurdDcHspTGFEgSdWBTfUIs-B7sYfZZddQaxpUAqngFFKTgOcJI.mp4?_nc_cat=105&_nc_sid=5e9851&_nc_ht=scontent-lga3-2.cdninstagram.com&_nc_ohc=BkB6Z-YT-kwQ7kNvwEy1RC1&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjQ5NzM5MTg4Mzk3MzgxNywidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjIyLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=9d88c27ad5c8d1fd&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9DQjQ4NTM5MDVFNTAxNzM4QTA0NzA4M0Q4Q0M1QThCMF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTko3eEI3LWFMdVJtbVVFQURhc0tzNXFTWWdKYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8tLfwavX7wgVAigCQzMsF0A2iHKwIMScGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=-b10hHYebN1xS9IQpZPDYQ&_nc_zt=28&oh=00_AfXzBrgy7ZEksJ9IRUuMLo8K9vmLRNr3DZhc0HyKn9hUYg&oe=68AD2352', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-3.cdninstagram.com/o1/v/t2/f2/m86/AQPI58l7udusy3Sng5v_hYvoCTFgyDyPhQYU0L6x7jORglnaTZUlLxEpQKZrUeUxJRPUvvBuwgzLmjSMqIApKegOrLu4WUrSMre6_eA.mp4?_nc_cat=108&_nc_sid=5e9851&_nc_ht=scontent-lga3-3.cdninstagram.com&_nc_ohc=F72POe4FZJkQ7kNvwEaAoyN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTM1MDQ1MzA0OTM4NjA3NSwidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjEzLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=c88671d11ff86515&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9BQzRCQ0JGQkIzNzc3NjlGMTBDOEU3RDMzMjc3QzBCMl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR1FaZlI0YWt5NjczaVFFQUZxM3FJTWxOaTRYYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmtpHbpNuO5gQVAigCQzMsF0ArcKPXCj1xGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=pV6IKdV5koF9SdY2Z0x3GQ&_nc_zt=28&oh=00_AfUC37IejXnAhdd8bqzOy6U3NtYDCwSaJHITFpURCtnkNA&oe=68AD0FCD', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-2.cdninstagram.com/o1/v/t2/f2/m86/AQPbJJSUVKR2E_KMyBIuNuHTgvfBYB79FoCjtj0-boVVHLWOWHNHJurdDcHspTGFEgSdWBTfUIs-B7sYfZZddQaxpUAqngFFKTgOcJI.mp4?_nc_cat=105&_nc_sid=5e9851&_nc_ht=scontent-lga3-2.cdninstagram.com&_nc_ohc=BkB6Z-YT-kwQ7kNvwEy1RC1&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MjQ5NzM5MTg4Mzk3MzgxNywidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjIyLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=9d88c27ad5c8d1fd&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9DQjQ4NTM5MDVFNTAxNzM4QTA0NzA4M0Q4Q0M1QThCMF92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HTko3eEI3LWFMdVJtbVVFQURhc0tzNXFTWWdKYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAm8tLfwavX7wgVAigCQzMsF0A2iHKwIMScGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=-b10hHYebN1xS9IQpZPDYQ&_nc_zt=28&oh=00_AfXzBrgy7ZEksJ9IRUuMLo8K9vmLRNr3DZhc0HyKn9hUYg&oe=68AD2352', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
            [ 'type' => 'video', 'src' => 'https://scontent-lga3-3.cdninstagram.com/o1/v/t2/f2/m86/AQPI58l7udusy3Sng5v_hYvoCTFgyDyPhQYU0L6x7jORglnaTZUlLxEpQKZrUeUxJRPUvvBuwgzLmjSMqIApKegOrLu4WUrSMre6_eA.mp4?_nc_cat=108&_nc_sid=5e9851&_nc_ht=scontent-lga3-3.cdninstagram.com&_nc_ohc=F72POe4FZJkQ7kNvwEaAoyN&efg=eyJ2ZW5jb2RlX3RhZyI6Inhwdl9wcm9ncmVzc2l2ZS5JTlNUQUdSQU0uQ0xJUFMuQzMuNzIwLmRhc2hfYmFzZWxpbmVfMV92MSIsInhwdl9hc3NldF9pZCI6MTM1MDQ1MzA0OTM4NjA3NSwidmlfdXNlY2FzZV9pZCI6MTAwOTksImR1cmF0aW9uX3MiOjEzLCJ1cmxnZW5fc291cmNlIjoid3d3In0%3D&ccb=17-1&vs=c88671d11ff86515&_nc_vs=HBksFQIYUmlnX3hwdl9yZWVsc19wZXJtYW5lbnRfc3JfcHJvZC9BQzRCQ0JGQkIzNzc3NjlGMTBDOEU3RDMzMjc3QzBCMl92aWRlb19kYXNoaW5pdC5tcDQVAALIARIAFQIYOnBhc3N0aHJvdWdoX2V2ZXJzdG9yZS9HR1FaZlI0YWt5NjczaVFFQUZxM3FJTWxOaTRYYnFfRUFBQUYVAgLIARIAKAAYABsCiAd1c2Vfb2lsATEScHJvZ3Jlc3NpdmVfcmVjaXBlATEVAAAmtpHbpNuO5gQVAigCQzMsF0ArcKPXCj1xGBJkYXNoX2Jhc2VsaW5lXzFfdjERAHX-B2XmnQEA&_nc_gid=pV6IKdV5koF9SdY2Z0x3GQ&_nc_zt=28&oh=00_AfUC37IejXnAhdd8bqzOy6U3NtYDCwSaJHITFpURCtnkNA&oe=68AD0FCD', 'url' => 'https://www.instagram.com/p/C_mILLOtMtM/', 'alt' => 'Reel 1' ],
        ];
    @endphp

    <x-shop::instagram.carousel :items="$media" />
</x-shop::layouts>
