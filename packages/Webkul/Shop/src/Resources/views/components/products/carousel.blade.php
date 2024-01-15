




<v-products-carousel
    src="{{ $src }}"
    title="{{ $title }}"
    navigation-link="{{ $navigationLink ?? '' }}"
>
    <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false"></x-shop::shimmer.products.carousel>
</v-products-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-products-carousel-template">
        <div class=" mt-20   max-sm:mt-[30px]" v-if="! isLoading && products.length">
            <div class="flex justify-between items-center w-full">
                <h3 class="text-[30px]  font-dmserif max-sm:text-[16px]" v-text="title"></h3>
          <div>
                <a
                :href="navigationLink"
                class="secondary-button block   mx-auto  border-0 text-base text-center"
                v-if="navigationLink"
            >
                @lang('shop::app.components.products.carousel.view-all')
            </a>
            </div>
            </div>

            <div
                ref="swiperContainer"
                class="flex gap-4 [&>*]:flex-[0] mt-[40px] overflow-auto scroll-smooth scrollbar-hide max-sm:mt-[20px]"
            >
                <x-shop::products.card
                    class="min-w-[217px] "
                    v-for="product in products"
                >
                </x-shop::products.card>
          
            </div>
            <div class="flex gap-8 justify-end items-center pt-5 ">
                    <span
                        class="icon-arrow-left-stylish rtl:icon-arrow-right-stylish inline-block text-[24px] cursor-pointer"
                        @click="swipeLeft"
                    >
                    </span>

                    <span
                        class="icon-arrow-right-stylish rtl:icon-arrow-left-stylish inline-block text-[24px] cursor-pointer"
                        @click="swipeRight"
                    >
                    </span>
                </div>
                
          
        </div>

        <!-- Product Card Listing -->
        <template v-if="isLoading">
            <x-shop::shimmer.products.carousel :navigation-link="$navigationLink ?? false"></x-shop::shimmer.products.carousel>
        </template>
    </script>

    <script type="module">
        app.component('v-products-carousel', {
            template: '#v-products-carousel-template',

            props: [
                'src',
                'title',
                'navigationLink',
            ],

            data() {
                return {
                    isLoading: true,

                    products: [],

                    offset: 323,
                };
            },

            mounted() {
                this.getProducts();
            },

            methods: {
                getProducts() {
                    this.$axios.get(this.src)
                        .then(response => {
                            this.isLoading = false;

                            this.products = response.data.data;
                        }).catch(error => {
                            console.log(error);
                        });
                },

                swipeLeft() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft -= this.offset;
                },

                swipeRight() {
                    const container = this.$refs.swiperContainer;

                    container.scrollLeft += this.offset;
                },
            },
        });
    </script>
@endPushOnce
