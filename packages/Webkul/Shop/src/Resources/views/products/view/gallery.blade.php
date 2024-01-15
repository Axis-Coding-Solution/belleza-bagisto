<v-product-gallery ref="gallery">
    <x-shop::shimmer.products.gallery/>
</v-product-gallery>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-gallery-template">
        <div class=" gap-[30px] h-max sticky top-[30px] max-1180:hidden">
        <div
                class="max-w-[560px] max-h-[548px] flex "
                v-show="! isMediaLoading"
            >
           
                <img 
                    class="min-w-[450px] " 
                    :src="baseFile.path" 
                    v-if="baseFile.type == 'image'"
                    alt="@lang('shop::app.products.view.gallery.product-image')"
                    width="560"
                    height="609"
                    @load="onMediaLoad()"
                />
                @if (core()->getConfigData('general.content.shop.wishlist_option'))
                                        <div
                                            class="flex items-center justify-end min-w-[46px] min-h-[46px] max-h-[46px] bg-white   text-[24px] transition-all hover:opacity-[0.8] cursor-pointer"
                                            :class="isWishlist ? 'icon-heart-fill text-[#A81D46]' : 'icon-heart '"
                                            @click="addToWishlist"
                                        >
                                        </div>
                                    @endif

                <div
                    class="min-w-[450px] rounded-[12px]"
                    v-if="baseFile.type == 'video'"
                >
                    <video  
                        controls                             
                        width='475'
                        @load="onMediaLoad()"
                    >
                        <source 
                            :src="baseFile.path" 
                            type="video/mp4"
                        />
                    </video>    
                </div>
                
            </div>
            <!-- Product Image Slider -->
            <div class="flex  gap-[10px] py-10 border-t-2 mt-4 items-center">
                <h1><</h1>
                <img 
                    :class="`min-w-[100px] max-h-[100px] rounded-[12px] ${ hover ? 'cursor-pointer' : '' }`" 
                    v-for="image in media.images"
                    :src="image.small_image_url"
                    alt="@lang('shop::app.products.view.gallery.thumbnail-image')"
                    width="100"
                    height="100"
                    @mouseover="change(image)"
                />
                <h1>></h1>

                <!-- Need to Set Play Button  -->
                <video 
                    class="min-w-[100px] rounded-[12px]"
                    v-for="video in media.videos"
                    @mouseover="change(video)"
                >
                    <source 
                        :src="video.video_url" 
                        type="video/mp4"
                    />
                </video>
            </div>
            
            <!-- Media shimmer Effect -->
            <div
                class="max-w-[560px] max-h-[609px]"
                v-show="isMediaLoading"
            >
                <div class="min-w-[560px] min-h-[607px] bg-[#E9E9E9] rounded-[12px] shimmer"></div>
            </div>

          
        </div>

        <!-- Product slider Image with shimmer -->
        <div class="flex gap-[30px] 1180:hidden overflow-auto scrollbar-hide">
            <x-shop::media.images.lazy
                ::src="image.large_image_url"
                class="min-w-[450px] max-sm:min-w-full w-[490px]" 
                v-for="image in media.images"
            >
            </x-shop::media.images.lazy>
        </div>
    </script>

    <script type="module">
        app.component('v-product-gallery', {
            template: '#v-product-gallery-template',

            data() {
                return {
                    isMediaLoading: true,

                    media: {
                        images: @json(product_image()->getGalleryImages($product)),

                        videos: @json(product_video()->getVideos($product)),
                    },

                    baseFile: {
                        type: '',

                        path: ''
                    },

                    hover: false,
                    isWishlist: Boolean("{{ (boolean) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"),

                    isCustomer: '{{ auth()->guard('customer')->check() }}',

                    is_buy_now: 0,
                }
            },

            watch: {
                'media.images': {
                    deep: true,

                    handler(newImages, oldImages) {
                        if (JSON.stringify(newImages) !== JSON.stringify(oldImages)) {
                            this.baseFile.path = newImages[0].large_image_url; 
                        }
                    },
                },
            },

            mounted() {
                if (this.media.images.length) {
                    this.baseFile.type = 'image';
                    this.baseFile.path = this.media.images[0].large_image_url;
                } else {
                    this.baseFile.type = 'video';
                    this.baseFile.path = this.media.videos[0].video_url;
                }
            },

            methods: {
                onMediaLoad() {
                    this.isMediaLoading = false;
                },

                change(file) {
                    if (file.type == 'videos') {
                        this.baseFile.type = 'video';

                        this.baseFile.path = file.video_url;
                    } else {
                        this.baseFile.type = 'image';

                        this.baseFile.path = file.large_image_url;
                    }
                    
                    this.hover = true;
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
            }
        })
    </script>
@endpushOnce
