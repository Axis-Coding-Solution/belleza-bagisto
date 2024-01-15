@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

{{-- SEO Meta Content --}}
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {{ app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) }}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

{{-- Page Layout --}}
<x-shop::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    {{-- Breadcrumbs --}}
    <div class="flex pb-5 max-lg:hidden">
        <x-shop::breadcrumbs name="product" :entity="$product"></x-shop::breadcrumbs>
    </div>

    {{-- Product Information Vue Component --}}
    <v-product :product-id="{{ $product->id }}">
        <x-shop::shimmer.products.view/>
    </v-product>

    {{-- Information Section --}}
    <div class="1180:mt-[80px]">
        <x-shop::tabs position="center">
            {{-- Description Tab --}}
            {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

            <x-shop::tabs.item
                class="container mt-[60px] !p-0 max-1180:hidden"
                :title="trans('shop::app.products.view.description')"
                :is-selected="true"
            >
                <div class="  max-1180:px-[20px]">
                    <p class="text-[#6E6E6E] text-[18px] max-1180:text-[14px]">
                        {!! $product->description !!}
                    </p>
                </div>
            </x-shop::tabs.item>

            {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}


            {{-- Additional Information Tab --}}
            <x-shop::tabs.item
                class="container mt-[60px] !p-0 max-1180:hidden"
                :title="trans('shop::app.products.view.additional-information')"
                :is-selected="false"
            >
                <div class="container mt-[60px] max-1180:px-[20px]">
                    <div class="grid gap-[15px] grid-cols-[auto_1fr] max-w-max mt-[30px]">
                        @foreach ($customAttributeValues as $customAttributeValue)
                            <div class="grid">
                                <p class="text-[16px] text-black">
                                    {!! $customAttributeValue['label'] !!}
                                </p>
                            </div>

                            @if (
                                $customAttributeValue['type'] == 'file'
                                && $customAttributeValue['value']
                            )
                                <a
                                    href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                    download="{{ $customAttributeValue['label'] }}"
                                >
                                    <span class="icon-download text-[24px]"></span>
                                </a>
                            @elseif (
                                $customAttributeValue['type'] == 'image'
                                && $customAttributeValue['value']
                            )
                                <a
                                    href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                    download="{{ $customAttributeValue['label'] }}"
                                >
                                    <img 
                                        class="h-[20px] w-[20px] min-h-[20px] min-w-[20px]"
                                        src="{{ Storage::url($customAttributeValue['value']) }}"
                                    />
                                </a>
                            @else 
                                <div class="grid">
                                    <p class="text-[16px] text-[#7D7D7D]">
                                        {!! $customAttributeValue['value'] ? $customAttributeValue['value'] : '-' !!}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </x-shop::tabs.item>

            {{-- Reviews Tab --}}
            <x-shop::tabs.item
                class="container mt-[60px] !p-0 max-1180:hidden"
                :title="trans('shop::app.products.view.review')"
                :is-selected="false"
            >
                @include('shop::products.view.reviews')
            </x-shop::tabs.item>
        </x-shop::tabs>
    </div>

    {{-- Information Section --}}
    <div class="sm:container sm:mt-[40px] max-1180:px-[20px] max-sm:px-10 1180:hidden">
        {{-- Description Accordion --}}
        <x-shop::accordion :is-active="true">
            <x-slot:header>
                <div class="flex justify-between mb-[20px] mt-[20px]">
                    <p class="text-[16px] font-medium 1180:hidden">
                        @lang('shop::app.products.view.description')
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                <p class="text-[#6E6E6E] text-[18px] max-1180:text-[14px] mb-[20px]">
                    {!! $product->description !!}
                </p>
            </x-slot:content>
        </x-shop::accordion>

        {{-- Additional Information Accordion --}}
        <x-shop::accordion :is-active="false">
            <x-slot:header>
                <div class="flex justify-between mb-[20px] mt-[20px]">
                    <p class="text-[16px] font-medium 1180:hidden">
                        @lang('shop::app.products.view.additional-information')
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                <div class="container mt-[20px] mb-[20px] max-1180:px-[20px]">
                    <p class="text-[#6E6E6E] text-[18px] max-1180:text-[14px]">
                        @foreach ($customAttributeValues as $customAttributeValue)
                            <div class="grid">
                                <p class="text-[16px] text-black">
                                    {{ $customAttributeValue['label'] }}
                                </p>
                            </div>

                            @if (
                                $customAttributeValue['type'] == 'file'
                                || $customAttributeValue['type'] == 'image'
                            )
                                <a
                                    href="{{ Storage::url($product[$customAttributeValue['code']]) }}"
                                    download="{{ $customAttributeValue['label'] }}"
                                >
                                    <p class="text-[16px] text-blue-500 underline">
                                        {{ $customAttributeValue['label'] }}
                                    </p>
                                </a>
                            @else 
                                <div class="grid">
                                    <p class="text-[16px] text-[#6E6E6E]">
                                        {{ $customAttributeValue['value'] ?? '-' }}
                                    </p>
                                </div>
                            @endif
                        @endforeach
                    </p>
                </div>
            </x-slot:content>
        </x-shop::accordion>

        {{-- Reviews Accordion --}}
        <x-shop::accordion :is-active="false">
            <x-slot:header>
                <div class="flex justify-between mb-[20px] mt-[20px]">
                    <p class="text-[16px] font-medium 1180:hidden">
                        @lang('shop::app.products.view.review')
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                @include('shop::products.view.reviews')
            </x-slot:content>
        </x-shop::accordion>
    </div>

    {{-- Featured Products --}}
    <x-shop::products.carousel
        :title="trans('shop::app.products.view.related-product-title')"
        :src="route('shop.api.products.related.index', ['id' => $product->id])"
    >
    </x-shop::products.carousel>

    {{-- Upsell Products --}}
    <x-shop::products.carousel
        :title="trans('shop::app.products.view.up-sell-title')"
        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
    >
    </x-shop::products.carousel>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <input 
                        type="hidden" 
                        name="product_id" 
                        value="{{ $product->id }}"
                    >

                    <input
                        type="hidden"
                        name="is_buy_now"
                        v-model="is_buy_now"
                    >
                    
                    <input 
                        type="hidden" 
                        name="quantity" 
                        :value="qty"
                    >

                    <div class="container px-[60px] max-1180:px-[0px] border-t">
                        <div class="flex gap-[40px] mt-[48px] max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-[25px]">
                            <!-- Gallery Blade Inclusion -->
                            @include('shop::products.view.gallery')

                            <!-- Details -->
                            <div class="max-w-[590px] relative max-1180:w-full max-1180:max-w-full max-1180:px-[20px]">
                                <h1 class="text-[#A81D46]">DERMA doctor</h1>
                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <div class="flex gap-[15px] justify-between">
                                    <h1 class="text-[30px] font-medium max-sm:text-[20px]">
                                        {{ $product->name }}
                                    </h1>
                                    <!-- heart svg shifted to gallery here the svg code is commented -->
<!-- 
                                    @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                        <div
                                            class="flex items-center justify-center min-w-[46px] min-h-[46px] max-h-[46px] bg-white   text-[24px] transition-all hover:opacity-[0.8] cursor-pointer"
                                            :class="isWishlist ? 'icon-heart-fill' : 'icon-heart'"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    @endif -->
                                </div>

                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <!-- Rating -->
                                {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

                                <div class="flex gap-[15px] items-center mt-[15px] ">
                                    <x-shop::products.star-rating 
                                        :value="$avgRatings"
                                        :is-editable=false
                                    >
                                    </x-shop::products.star-rating>

                                    <div class="flex gap-[15px] items-center">
                                        <p class="text-[#A81D46] text-[14px]">
                                          4.7  ({{ $product->approvedReviews->count() }} @lang('reviews'))
                                        </p>
                                    </div>
                                </div>

                                {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}
                              <div class="flex flex-col sm:flex-row sm:gap-6 pt-4">
                                <div class="flex gap-2">
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.4673 5.34864C12.2232 4.8434 11.8738 4.38977 11.4284 4.00024C10.9831 3.61058 10.4647 3.30477 9.8874 3.0912C9.28976 2.86981 8.65493 2.75781 8.00034 2.75781C7.34576 2.75781 6.71093 2.86981 6.11329 3.0912C5.53587 3.30477 5.01744 3.61058 4.57227 4.00024C4.12693 4.38991 3.77744 4.84354 3.53336 5.34864C3.28034 5.87158 3.15234 6.42705 3.15234 6.99981C3.15234 7.57258 3.28034 8.12805 3.53336 8.65099C3.77744 9.15622 4.12693 9.60985 4.57227 9.99938C5.0176 10.389 5.53603 10.6949 6.11329 10.9084C6.71093 11.1298 7.34576 11.2418 8.00034 11.2418C8.65493 11.2418 9.28976 11.1298 9.8874 10.9084C10.4648 10.6949 10.9832 10.389 11.4284 9.99938C11.8738 9.60971 12.2232 9.15609 12.4673 8.65099C12.7203 8.12805 12.8483 7.57258 12.8483 6.99981C12.8483 6.42705 12.7203 5.87158 12.4673 5.34864ZM7.3845 9.59818L6.09666 8.63095L4.85634 7.69954L5.95846 6.57597L7.09838 7.43203L9.77791 4.40144L11.1439 5.2752L7.3845 9.59818Z" fill="#3BCC83"/>
<path d="M16 7.00329L15.1065 5.76635L15.3928 4.32476L14.0278 3.47996L13.6591 2.05306L12.0282 1.73078L11.0649 0.534745L9.4171 0.781392L8.00377 0L6.59012 0.781804L4.94243 0.531314L3.97694 1.72571L2.3462 2.04825L1.97804 3.47529L0.611137 4.31818L0.89302 5.76004L0 6.99671L0.89349 8.23365L0.607216 9.67537L1.97224 10.5202L2.34086 11.9471L3.97176 12.2692L4.93506 13.4653L6.5829 13.2186L7.99624 14L9.40988 13.2182L11.0574 13.4687L12.0229 12.2743L13.6536 11.9517L14.022 10.5247L15.3889 9.68182L15.107 8.23996L16 7.00329ZM8 11.9775C4.8582 11.9775 2.31137 9.74908 2.31137 7C2.31137 4.25092 4.8582 2.02245 8 2.02245C11.1418 2.02245 13.6886 4.25092 13.6886 7C13.6886 9.74908 11.1418 11.9775 8 11.9775Z" fill="#3BCC83"/>
</svg>
<h1>00% Genuine Brands </h1>
                                </div>
                                <div class="flex gap-2 items-center">
                                <svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M0.445165 0.925781H11.3922C11.508 0.925781 11.603 1.00442 11.603 1.10132V7.57461C11.603 7.67101 11.5086 7.75016 11.3922 7.75016H0.445165C0.329413 7.75016 0.234375 7.67101 0.234375 7.57461V1.10132C0.234375 1.00442 0.329413 0.925781 0.445165 0.925781Z" fill="#CA0007"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M0.566406 1.20117V1.63394H11.2734V1.20117H0.566406ZM0.566406 1.78209V2.68568H11.2734V1.78209H0.566406ZM0.566406 2.83383V3.73742H11.2734V2.83383H0.566406ZM0.566406 3.88607V4.78966H11.2734V3.88607H0.566406ZM0.566406 4.93781V5.8414H11.2734V4.93781H0.566406ZM0.566406 5.98955V6.89314H11.2734V5.98955H0.566406ZM0.566406 7.0418V7.47457H11.2734V7.0418H0.566406Z" fill="#A11211"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.95395 2.83398C6.92139 2.83398 7.70546 3.50724 7.70546 4.33777C7.70546 4.37278 7.70424 4.40728 7.70119 4.44127L6.83367 4.13838L7.19432 3.84361L4.9987 3.07701C5.27345 2.92328 5.60182 2.83398 5.95395 2.83398ZM7.40877 5.17592C7.09441 5.57774 6.56013 5.84156 5.95395 5.84156C5.0517 5.84156 4.30845 5.25557 4.2128 4.50266L5.31427 4.69038L4.87381 5.0506L7.40877 5.17592ZM4.51132 3.17746L3.37695 4.1049L5.92227 4.53868L5.56161 4.83345L8.46211 4.97703L6.30608 4.22463L6.66674 3.92986L4.51132 3.17746Z" fill="white"/>
<path d="M11.9598 7.9375H0.0410156V8.31497H11.9598V7.9375Z" fill="black"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.0078 1.81641H16.0719C16.1724 1.81641 16.2589 1.8641 16.2979 1.94121L17.9812 5.28466C17.9946 5.31104 18 5.33488 18 5.3633V9.69202C18 9.76609 17.9269 9.82697 17.838 9.82697H17.1496C17.1496 8.8625 16.2108 8.08067 15.0526 8.08067C13.8945 8.08067 12.9557 8.8625 12.9557 9.82697H12.0078C11.9188 9.82697 11.8457 9.76609 11.8457 9.69202V1.95136C11.8457 1.87729 11.9188 1.81641 12.0078 1.81641Z" fill="#CA0007"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.1523 2.39849C12.1523 2.21889 12.3284 2.07227 12.5441 2.07227H15.7711C15.9313 2.07227 16.0702 2.14938 16.1324 2.27216L17.4142 4.81805C17.466 4.92053 17.4532 5.03215 17.3788 5.125C17.3051 5.21784 17.1863 5.27061 17.0529 5.27061H15.642C15.5341 5.27061 15.4373 5.23661 15.3617 5.17269L14.7933 4.68867C14.7732 4.67142 14.7519 4.66432 14.7232 4.66432H12.5441C12.3284 4.66432 12.1523 4.5177 12.1523 4.33809V2.39849Z" fill="#A11211"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.2988 2.39731V4.33641C12.2988 4.44853 12.4091 4.54036 12.5437 4.54036H14.7229C14.7911 4.54036 14.8502 4.56117 14.8978 4.60175L15.4662 5.08577C15.5137 5.12635 15.5728 5.14716 15.641 5.14716H17.052C17.1366 5.14716 17.2091 5.11469 17.256 5.05634C17.303 4.998 17.3109 4.9295 17.278 4.86456L15.9962 2.31868C15.9572 2.24105 15.8713 2.19336 15.7702 2.19336H12.5431C12.4091 2.19336 12.2988 2.28468 12.2988 2.39731Z" fill="white"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.3711 5.43782V8.90303C12.3711 8.98015 12.4235 9.04813 12.4996 9.08263C12.8688 8.20492 13.8722 7.57529 15.0523 7.57529C15.9978 7.57529 16.8294 7.97915 17.3131 8.59101V6.04512C17.3131 5.933 17.2028 5.84117 17.0682 5.84117H15.5025C15.4342 5.84117 15.3752 5.82037 15.3276 5.77978L14.7592 5.29577C14.7117 5.25518 14.6526 5.23438 14.5844 5.23438H12.6166C12.4814 5.23387 12.3711 5.3257 12.3711 5.43782Z" fill="#A11211"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M17.9992 5.88477H17.6026C17.5539 5.88477 17.5137 5.91825 17.5137 5.95884V8.7031C17.5137 8.74369 17.5539 8.77717 17.6026 8.77717H17.9992V5.88477Z" fill="black"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M17.9993 6.07812H17.7221C17.6965 6.07812 17.6758 6.09538 17.6758 6.11668V6.2075C17.6758 6.22881 17.6965 6.24606 17.7221 6.24606H17.9993V6.07812ZM17.9993 8.58342V8.41498H17.7221C17.6965 8.41498 17.6758 8.43223 17.6758 8.45354V8.54436C17.6758 8.56567 17.6965 8.58292 17.7221 8.58292H17.9993V8.58342ZM17.9993 8.32366V8.15522H17.7221C17.6965 8.15522 17.6758 8.17247 17.6758 8.19378V8.28459C17.6758 8.3059 17.6965 8.32315 17.7221 8.32315H17.9993V8.32366ZM17.9993 8.0639V7.89546H17.7221C17.6965 7.89546 17.6758 7.91271 17.6758 7.93401V8.02483C17.6758 8.04614 17.6965 8.06339 17.7221 8.06339H17.9993V8.0639ZM17.9993 7.80464V7.6362H17.7221C17.6965 7.6362 17.6758 7.65345 17.6758 7.67476V7.76557C17.6758 7.78688 17.6965 7.80413 17.7221 7.80413H17.9993V7.80464ZM17.9993 7.54488V7.37644H17.7221C17.6965 7.37644 17.6758 7.39369 17.6758 7.41499V7.50581C17.6758 7.52712 17.6965 7.54437 17.7221 7.54437H17.9993V7.54488ZM17.9993 7.28511V7.11667H17.7221C17.6965 7.11667 17.6758 7.13392 17.6758 7.15523V7.24605C17.6758 7.26736 17.6965 7.28461 17.7221 7.28461H17.9993V7.28511ZM17.9993 7.02535V6.85691H17.7221C17.6965 6.85691 17.6758 6.87416 17.6758 6.89547V6.98628C17.6758 7.00759 17.6965 7.02484 17.7221 7.02484H17.9993V7.02535ZM17.9993 6.76609V6.59765H17.7221C17.6965 6.59765 17.6758 6.6149 17.6758 6.63621V6.72703C17.6758 6.74834 17.6965 6.76559 17.7221 6.76559H17.9993V6.76609ZM17.9993 6.50633V6.33789H17.7221C17.6965 6.33789 17.6758 6.35514 17.6758 6.37645V6.46726C17.6758 6.48857 17.6965 6.50582 17.7221 6.50582H17.9993V6.50633Z" fill="#4E4E4E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.8639 5.51367H13.3982C13.4402 5.51367 13.4749 5.54259 13.4749 5.5776V5.67197C13.4749 5.70697 13.4402 5.73589 13.3982 5.73589H12.8639C12.8218 5.73589 12.7871 5.70697 12.7871 5.67197V5.5776C12.7871 5.54259 12.8218 5.51367 12.8639 5.51367Z" fill="#CA0007"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M17.9999 6.43555C17.8311 6.55833 17.7227 6.7496 17.7227 6.96522C17.7227 7.18085 17.8311 7.37212 17.9999 7.4949V6.43555Z" fill="#EEB400"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M9.18261 7.9375H10.387C10.9688 7.9375 11.4446 8.33374 11.4446 8.81826C11.4446 9.30278 10.9688 9.69902 10.387 9.69902H9.18261C8.6008 9.69902 8.125 9.30278 8.125 8.81826C8.125 8.33425 8.6008 7.9375 9.18261 7.9375Z" fill="#CA0007"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M9.45432 7.9375V9.69902H9.18261C9.15885 9.69902 9.13509 9.69851 9.11133 9.69699V7.93953C9.13509 7.93801 9.15885 7.9375 9.18261 7.9375H9.45432ZM10.1153 7.9375H10.387C10.4108 7.9375 10.4346 7.93801 10.4583 7.93953V9.69699C10.4346 9.69851 10.4108 9.69902 10.387 9.69902H10.1153V7.9375Z" fill="#A11211"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.0548 8.20898C16.1326 8.20898 17.0062 8.93653 17.0062 9.83403C17.0062 10.7315 16.1326 11.4591 15.0548 11.4591C13.9771 11.4591 13.1035 10.7315 13.1035 9.83403C13.1035 8.93653 13.9771 8.20898 15.0548 8.20898ZM15.0548 8.83556C14.3926 8.83556 13.8553 9.28254 13.8553 9.83454C13.8553 10.386 14.392 10.8335 15.0548 10.8335C15.7171 10.8335 16.2544 10.3865 16.2544 9.83454C16.2538 9.28254 15.7171 8.83556 15.0548 8.83556Z" fill="#111C3E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.055 8.83594C15.7172 8.83594 16.2546 9.28291 16.2546 9.83491C16.2546 10.3864 15.7179 10.8339 15.055 10.8339C14.3928 10.8339 13.8555 10.3869 13.8555 9.83491C13.8555 9.28291 14.3922 8.83594 15.055 8.83594ZM14.6255 9.37424L14.7979 9.51782C14.8485 9.48941 14.9058 9.46911 14.9673 9.45947V9.25653C14.8394 9.26922 14.7224 9.31133 14.6255 9.37424ZM14.6743 9.62081L14.5019 9.47723C14.4263 9.5579 14.3764 9.65531 14.3605 9.76185H14.6042C14.6158 9.7101 14.6401 9.66241 14.6743 9.62081ZM14.6042 9.90746H14.3605C14.3764 10.014 14.4269 10.1114 14.5019 10.1921L14.6743 10.0485C14.6401 10.0069 14.6158 9.95921 14.6042 9.90746ZM14.7979 10.1515L14.6255 10.2951C14.7224 10.358 14.8394 10.3996 14.9673 10.4128V10.2098C14.9058 10.2002 14.8485 10.1799 14.7979 10.1515ZM15.1421 10.2104V10.4133C15.2701 10.4001 15.387 10.358 15.4839 10.2956L15.3115 10.152C15.2615 10.1799 15.2043 10.2002 15.1421 10.2104ZM15.4358 10.0485L15.6082 10.1921C15.6837 10.1114 15.7337 10.014 15.7495 9.90746H15.5058C15.4937 9.95921 15.4693 10.0069 15.4358 10.0485ZM15.5058 9.76185H15.7495C15.7337 9.65531 15.6831 9.5579 15.6082 9.47723L15.4358 9.62081C15.4693 9.66241 15.4937 9.7101 15.5058 9.76185ZM15.3115 9.51782L15.4839 9.37424C15.387 9.31133 15.2701 9.26972 15.1421 9.25653V9.45947C15.2043 9.46911 15.2615 9.48941 15.3115 9.51782Z" fill="#90A1A1"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.0556 8.9375C15.6502 8.9375 16.1327 9.33881 16.1327 9.8345C16.1327 10.3297 15.6508 10.7315 15.0556 10.7315C14.461 10.7315 13.9785 10.3302 13.9785 9.8345C13.9785 9.33881 14.4604 8.9375 15.0556 8.9375ZM15.0556 9.07854C14.5542 9.07854 14.1479 9.41695 14.1479 9.8345C14.1479 10.252 14.5542 10.5904 15.0556 10.5904C15.557 10.5904 15.9634 10.252 15.9634 9.8345C15.9634 9.41695 15.557 9.07854 15.0556 9.07854Z" fill="#979797"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M15.0537 9.66406C15.1664 9.66406 15.2578 9.74017 15.2578 9.83403C15.2578 9.92789 15.1664 10.004 15.0537 10.004C14.941 10.004 14.8496 9.92789 14.8496 9.83403C14.8496 9.74017 14.941 9.66406 15.0537 9.66406Z" fill="#979797"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.94156 8.20898C7.01927 8.20898 7.8929 8.93653 7.8929 9.83403C7.8929 10.7315 7.01927 11.4591 5.94156 11.4591C4.86386 11.4591 3.99023 10.7315 3.99023 9.83403C3.99023 8.93653 4.86386 8.20898 5.94156 8.20898ZM5.94156 8.83556C5.27934 8.83556 4.74201 9.28254 4.74201 9.83454C4.74201 10.386 5.27873 10.8335 5.94156 10.8335C6.60379 10.8335 7.14112 10.3865 7.14112 9.83454C7.14112 9.28254 6.60379 8.83556 5.94156 8.83556Z" fill="#111C3E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.94174 8.83594C6.60396 8.83594 7.14129 9.28291 7.14129 9.83491C7.14129 10.3864 6.60457 10.8339 5.94174 10.8339C5.27952 10.8339 4.74219 10.3869 4.74219 9.83491C4.74219 9.28291 5.27952 8.83594 5.94174 8.83594ZM5.51224 9.37424L5.68465 9.51782C5.73522 9.48941 5.79248 9.46911 5.85401 9.45947V9.25653C5.72669 9.26922 5.60911 9.31133 5.51224 9.37424ZM5.56098 9.62081L5.38857 9.47723C5.31303 9.5579 5.26307 9.65531 5.24723 9.76185H5.49092C5.5031 9.7101 5.52686 9.66241 5.56098 9.62081ZM5.49092 9.90746H5.24723C5.26307 10.014 5.31364 10.1114 5.38857 10.1921L5.56098 10.0485C5.52686 10.0069 5.5031 9.95921 5.49092 9.90746ZM5.68465 10.1515L5.51224 10.2951C5.60911 10.358 5.72608 10.3996 5.85401 10.4128V10.2098C5.79248 10.2002 5.73522 10.1799 5.68465 10.1515ZM6.02947 10.2104V10.4133C6.1574 10.4001 6.27437 10.358 6.37124 10.2956L6.19883 10.152C6.14827 10.1799 6.091 10.2002 6.02947 10.2104ZM6.3225 10.0485L6.49491 10.1921C6.57046 10.1114 6.62041 10.014 6.63686 9.90746H6.39317C6.38099 9.95921 6.35662 10.0069 6.3225 10.0485ZM6.39256 9.76185H6.63625C6.62041 9.65531 6.56985 9.5579 6.49491 9.47723L6.3225 9.62081C6.35662 9.66241 6.38099 9.7101 6.39256 9.76185ZM6.19883 9.51782L6.37124 9.37424C6.27437 9.31133 6.1574 9.26972 6.02947 9.25653V9.45947C6.091 9.46911 6.14827 9.48941 6.19883 9.51782Z" fill="#90A1A1"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.94233 8.9375C6.53693 8.9375 7.01943 9.33881 7.01943 9.8345C7.01943 10.3297 6.53754 10.7315 5.94233 10.7315C5.34774 10.7315 4.86523 10.3302 4.86523 9.8345C4.86584 9.33881 5.34774 8.9375 5.94233 8.9375ZM5.94233 9.07854C5.44095 9.07854 5.0346 9.41695 5.0346 9.8345C5.0346 10.252 5.44095 10.5904 5.94233 10.5904C6.44372 10.5904 6.85007 10.252 6.85007 9.8345C6.85007 9.41695 6.44372 9.07854 5.94233 9.07854Z" fill="#979797"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.94237 9.66406C6.05508 9.66406 6.14646 9.74017 6.14646 9.83403C6.14646 9.92789 6.05508 10.004 5.94237 10.004C5.82966 10.004 5.73828 9.92789 5.73828 9.83403C5.73828 9.74017 5.82966 9.66406 5.94237 9.66406Z" fill="#979797"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.95133 8.20898C3.02904 8.20898 3.90266 8.93653 3.90266 9.83403C3.90266 10.7315 3.02904 11.4591 1.95133 11.4591C0.873621 11.4596 0 10.732 0 9.83403C0 8.93653 0.873621 8.20898 1.95133 8.20898ZM1.95133 8.83556C1.28911 8.83556 0.751777 9.28254 0.751777 9.83454C0.751777 10.386 1.2885 10.8335 1.95133 10.8335C2.61416 10.8335 3.15088 10.3865 3.15088 9.83454C3.15088 9.28254 2.61355 8.83556 1.95133 8.83556Z" fill="#111C3E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.95151 8.83594C2.61373 8.83594 3.15106 9.28291 3.15106 9.83491C3.15106 10.3864 2.61434 10.8339 1.95151 10.8339C1.28868 10.8339 0.751953 10.3869 0.751953 9.83491C0.751953 9.28291 1.28928 8.83594 1.95151 8.83594ZM1.52201 9.37424L1.69442 9.51782C1.74498 9.48941 1.80225 9.46911 1.86378 9.45947V9.25653C1.73645 9.26922 1.61887 9.31133 1.52201 9.37424ZM1.57074 9.62081L1.39834 9.47723C1.32279 9.5579 1.27284 9.65531 1.257 9.76185H1.50068C1.51226 9.7101 1.53663 9.66241 1.57074 9.62081ZM1.50068 9.90746H1.257C1.27284 10.014 1.3234 10.1114 1.39834 10.1921L1.57074 10.0485C1.53663 10.0069 1.51226 9.95921 1.50068 9.90746ZM1.69442 10.1515L1.52201 10.2951C1.61887 10.358 1.73584 10.3996 1.86378 10.4128V10.2098C1.80225 10.2002 1.74498 10.1799 1.69442 10.1515ZM2.03923 10.2104V10.4133C2.16717 10.4001 2.28414 10.358 2.38101 10.2956L2.2086 10.152C2.15803 10.1799 2.10077 10.2002 2.03923 10.2104ZM2.33227 10.0485L2.50468 10.1921C2.58022 10.1114 2.63018 10.014 2.64602 9.90746H2.40233C2.39075 9.95921 2.36638 10.0069 2.33227 10.0485ZM2.40233 9.76185H2.64602C2.63018 9.65531 2.57961 9.5579 2.50468 9.47723L2.33227 9.62081C2.36638 9.66241 2.39075 9.7101 2.40233 9.76185ZM2.2086 9.51782L2.38101 9.37424C2.28414 9.31133 2.16717 9.26972 2.03923 9.25653V9.45947C2.10077 9.46911 2.15803 9.48941 2.2086 9.51782Z" fill="#90A1A1"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.9521 8.9375C2.5467 8.9375 3.0292 9.33881 3.0292 9.8345C3.0292 10.3297 2.54731 10.7315 1.9521 10.7315C1.3575 10.7315 0.875 10.3302 0.875 9.8345C0.875 9.33881 1.3575 8.9375 1.9521 8.9375ZM1.9521 9.07854C1.45071 9.07854 1.04436 9.41695 1.04436 9.8345C1.04436 10.252 1.45071 10.5904 1.9521 10.5904C2.45349 10.5904 2.85984 10.252 2.85984 9.8345C2.85984 9.41695 2.45349 9.07854 1.9521 9.07854Z" fill="#979797"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M1.95214 9.66406C2.06484 9.66406 2.15622 9.74017 2.15622 9.83403C2.15622 9.92789 2.06484 10.004 1.95214 10.004C1.83943 10.004 1.74805 9.92789 1.74805 9.83403C1.74805 9.74017 1.83943 9.66406 1.95214 9.66406Z" fill="#979797"/>
</svg>
<h1>Fast Delivery</h1>
                                </div>
                              </div>
                                <!-- Pricing -->
                                {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                                <!-- <p class="flex gap-2.5 items-center mt-[25px] text-[24px] !font-medium max-sm:mt-[15px] max-sm:text-[18px]">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}

                                    <span class="text-[18px] text-[#6E6E6E]">
                                        @if (
                                            (bool) core()->getConfigData('taxes.catalogue.pricing.tax_inclusive') 
                                            && $product->getTypeInstance()->getTaxCategory()
                                        )
                                            @lang('shop::app.products.view.tax-inclusive')
                                        @endif
                                    </span>
                                </p> -->

                                {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                                {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                                <!-- <p class="mt-[25px] text-[18px] text-[#6E6E6E] max-sm:text-[14px] max-sm:mt-[15px]">
                                    {!! $product->short_description !!}
                                </p> -->

                                {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                                @include('shop::products.view.types.configurable')

                                @include('shop::products.view.types.grouped')

                                @include('shop::products.view.types.bundle')

                                @include('shop::products.view.types.downloadable')

                              <div> 
                                <h1 class="pt-5 text-[10px] sm:text-[14px]">Same day delivery if you place order before 6 PM!</h1>
                                <div class="flex gap-6 pt-4">
                                    <h1 class="text-black text-[24px] font-bold">19 BD</h1>
                                    <h1 class="text-[#D9D9D9] text-[24px] ">36 BD</h1>
                                </div>
                                <h1 class="text-[9px] text-[#A81D46]">VAT Included</h1>
                              </div>
                                <!-- Product Actions and Qunatity Box -->
                                <div class=" gap-[15px] max-w-[470px] mt-[30px]">

                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <x-shop::quantity-changer
                                            name="quantity"
                                            value="1"
                                            class="gap-x-[16px] py-[15px] px-[26px] rounded-[12px]"
                                        >
                                        </x-shop::quantity-changer>
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                    <!-- Add To Cart Button -->
                                    <div class="flex gap-14 pt-5">
                                    {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}

<button
    type="submit"
    class=" w-[233px] sm:h-[65px] h-[30px] px-5 sm:px-0 border bg-[#A81D46] border-black text-white text-[8px] sm:text-[20px] "
    {{ ! $product->isSaleable(1) ? 'disabled' : '' }}
>
    @lang('shop::app.products.view.add-to-cart')
</button>

{!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}
<button class="border w-[233px] sm:h-[65px] h-[30px] px-5 sm:px-0 border-black bg-[#FCE4DE] text-[8px] sm:text-[20px]" >Buy Now</button>
                                    </div>
                                   
                                   
                                </div>

                                <!-- Buy Now Button -->
                                {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                                @if (core()->getConfigData('catalog.products.storefront.buy_now_button_display'))
                                    <button
                                        type="submit"
                                        class="primary-button w-full max-w-[470px] mt-[20px]"
                                        @click="is_buy_now=1;"
                                        {{ ! $product->isSaleable(1) ? 'disabled' : '' }}
                                    >
                                        @lang('shop::app.products.view.buy-now')
                                    </button>
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}

                                <!-- Share Buttons -->
                                <div class="flex gap-[35px] mt-[40px] max-sm:flex-wrap max-sm:justify-center">
                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                    <!-- <div
                                        class="flex gap-[10px] justify-center items-center cursor-pointer"
                                        @click="is_buy_now=0; addToCompare({{ $product->id }})"
                                    >
                                        @if (core()->getConfigData('general.content.shop.compare_option'))
                                            <span class="icon-compare text-[24px]"></span>

                                            @lang('shop::app.products.view.compare')
                                        @endif
                                    </div> -->

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </x-shop::form>
            
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                props: ['productId'],

                data() {
                    return {
                        isWishlist: Boolean("{{ (boolean) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"),

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        is_buy_now: 0,
                    }
                },

                methods: {
                    addToCart(params) {
                        let formData = new FormData(this.$refs.formData);

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    if (response.data.redirect) {
                                        window.location.href= response.data.redirect;
                                    }
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }
                            })
                            .catch(error => {});
                    },

                    addToWishlist() {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                    product_id: "{{ $product->id }}"
                                })
                                .then(response => {
                                    this.isWishlist = ! this.isWishlist;

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {});
                        } else {
                            window.location.href = "{{ route('shop.customer.session.index')}}";
                        }
                    },

                    addToCompare(productId) {
                        /**
                         * This will handle for customers.
                         */
                        if (this.isCustomer) {
                            this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                    'product_id': productId
                                })
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                })
                                .catch(error => {
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                        return;
                                    }

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                });

                            return;
                        }

                        /**
                         * This will handle for guests.
                         */
                        let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                        if (existingItems.length) {
                            if (! existingItems.includes(productId)) {
                                existingItems.push(productId);

                                this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                                this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "@lang('shop::app.products.view.already-in-compare')" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "@lang('shop::app.products.view.add-to-compare')" });
                        }
                    },

                    getCompareItemsStorageKey() {
                        return 'compare_items';
                    },

                    setStorageValue(key, value) {
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
