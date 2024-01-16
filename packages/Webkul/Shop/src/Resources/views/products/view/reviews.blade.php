{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

<v-product-review :product-id="{{ $product->id }}">
    <div class="container max-1180:px-[20px]">
        <x-shop::shimmer.products.reviews/>
    </div>
</v-product-review>

{!! view_render_event('bagisto.shop.products.view.reviews.after', ['product' => $product]) !!}

@pushOnce('scripts')
    {{-- Product Review Template --}}
    <script type="text/x-template" id="v-product-review-template">
    <div class="flex flex-col xl:flex-row  xl:gap-[230px]  pb-3  border-none">
                           
                           <v-product-review-item
                               v-for='review in reviews'
                               :review="review"
                           ></v-product-review-item> 
                         
                       </div>
        <div class=" ">
            <!-- Create Review Form Container -->
            <div 
                class="w-full" 
                v-if="canReview"
            >
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <!-- Review Form -->
                    <form
                        class="xl:w-[752px]"
                        @submit="handleSubmit($event, store)"
                        enctype="multipart/form-data"
                    >
                        <div class="max-w-[286px]">
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.control
                                    type="image"
                                    name="attachments"
                                    class="!p-0 !mb-0"
                                    rules="required"
                                    ref="reviewImages"
                                    :label="trans('shop::app.products.view.reviews.attachments')"
                                    :is-multiple="true"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    class="mt-4"
                                    control-name="attachments"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>
                        </div>
                        
                        <div>
                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="mt-[0] required">
                                    @lang('shop::app.products.view.reviews.rating')
                                </x-shop::form.control-group.label>

                                <x-shop::products.star-rating
                                    name="rating"
                                    :value="old('rating') ?? 5"
                                    :disabled="false"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.rating')"
                                >
                                </x-shop::products.star-rating>

                                <x-shop::form.control-group.error
                                    control-name="rating"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            @if (
                                core()->getConfigData('catalog.products.review.guest_review')
                                && ! auth()->guard('customer')->user()
                            )
                                <x-shop::form.control-group>
                                    <x-shop::form.control-group.label class="required">
                                        @lang('shop::app.products.view.reviews.name')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        rules="required"
                                        :label="trans('shop::app.products.view.reviews.name')"
                                        :placeholder="trans('shop::app.products.view.reviews.name')"
                                    >
                                    </x-shop::form.control-group.control>

                                    <x-shop::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-shop::form.control-group.error>
                                </x-shop::form.control-group>
                            @endif

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.products.view.reviews.title')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    name="title"
                                    :value="old('title')"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.title')"
                                    :placeholder="trans('shop::app.products.view.reviews.title')"
                                    class="h-[44px]"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    control-name="title"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>

                            <x-shop::form.control-group>
                                <x-shop::form.control-group.label class="required">
                                    @lang('shop::app.products.view.reviews.comment')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="textarea"
                                    rows="12"
                                    name="comment"
                                    :value="old('comment')"
                                    rules="required"
                                    :label="trans('shop::app.products.view.reviews.comment')"
                                    :placeholder="trans('shop::app.products.view.reviews.comment')"
                                    class="h-[110px]"
                                >
                                </x-shop::form.control-group.control>

                                <x-shop::form.control-group.error
                                    control-name="comment"
                                >
                                </x-shop::form.control-group.error>
                            </x-shop::form.control-group>


                            <div class="flex justify-end mb-10">
                                <button
                                    class="primary-button  w-[210px] py-0  rounded-none border-black text-black bg-[#FCE4DE] text-center h-[44px]"
                                    type='submit'
                                >
                                    @lang('shop::app.products.view.reviews.submit-review')
                                </button>
                                
                                <!-- <button
                                    type="button"
                                    class="secondary-button items-center px-[30px] py-[10px] rounded-[18px] max-sm:w-full max-sm:max-w-[374px]"
                                    @click="canReview = false"
                                >
                                    @lang('shop::app.products.view.reviews.cancel')
                                </button> -->
                            </div>
                        </div>
                    </form>
                </x-shop::form>
            </div>

            <!-- Product Reviews Container -->
            <div v-else>
                <!-- Review Container Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.products.reviews/>
                </template>

                <template v-else>
                    <!-- Review Section Header -->
                    <div class="flex gap-[15px] items-center justify-between  max-sm:flex-wrap">
                        <!-- <h3 class="font-dmserif text-[30px] max-sm:text-[22px]">
                            @lang('shop::app.products.view.reviews.customer-review')
                        </h3> -->
                        
                        @if (
                            core()->getConfigData('catalog.products.review.guest_review')
                            || auth()->guard('customer')->user()
                        )
                            <div
                                class="flex gap-x-[15px] items-center px-[15px] py-[10px] border border-navyBlue rounded-[12px] cursor-pointer"
                                @click="canReview = true"
                            >
                                <span class="icon-pen text-[24px]"></span>

                                @lang('shop::app.products.view.reviews.write-a-review')
                            </div>
                        @endif
                    </div>

                    <template v-if="reviews.length">
                     
                        <!-- <div class="flex gap-[15px] justify-between items-center max-w-[365px] mt-[30px] max-sm:flex-wrap">
                            <p class="text-[30px] font-medium max-sm:text-[16px]">{{ number_format($avgRatings, 1) }}</p>

                            <x-shop::products.star-rating :value="$avgRatings"></x-shop::products.star-rating>

                            <p class="text-[12px] text-[#858585]">
                                (@{{ meta.total }} @lang('shop::app.products.view.reviews.customer-review'))
                            </p>
                        </div> -->

                 
                        <!-- <div class="flex gap-x-[20px] items-center">
                            <div class="flex gap-y-[18px] flex-wrap max-w-[365px] mt-[10px]">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                        <div class="text-[16px] font-medium">{{ $i }} Stars</div>
                                        <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                            <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: {{ $percentageRatings[$i] }}%"></div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div> -->

                        <!-- <div class="flex flex-col sm:flex-row gap-[50px] lg:gap-[230px] md:mt-[60px] pb-3  border-none">
                           
                            <v-product-review-item
                                v-for='review in reviews'
                                :review="review"
                            ></v-product-review-item> 
                          
                        </div> -->
                        <!-- <div class="flex flex-col gap-4 sm:mt-5 pt-3">
                            <h1 class="text-[18px] leading-[27px]">Rating</h1>
                            <svg width="112" height="16" viewBox="0 0 112 16" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_1551_15192)">
<path d="M7.9987 1.33398L10.0587 5.50732L14.6654 6.18065L11.332 9.42732L12.1187 14.014L7.9987 11.8473L3.8787 14.014L4.66536 9.42732L1.33203 6.18065L5.9387 5.50732L7.9987 1.33398Z" fill="#A6A6A6" stroke="#A6A6A6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<g clip-path="url(#clip1_1551_15192)">
<path d="M31.9987 1.33398L34.0587 5.50732L38.6654 6.18065L35.332 9.42732L36.1187 14.014L31.9987 11.8473L27.8787 14.014L28.6654 9.42732L25.332 6.18065L29.9387 5.50732L31.9987 1.33398Z" fill="#A6A6A6" stroke="#A6A6A6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<g clip-path="url(#clip2_1551_15192)">
<path d="M55.9987 1.33398L58.0587 5.50732L62.6654 6.18065L59.332 9.42732L60.1187 14.014L55.9987 11.8473L51.8787 14.014L52.6654 9.42732L49.332 6.18065L53.9387 5.50732L55.9987 1.33398Z" fill="#A6A6A6" stroke="#A6A6A6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<g clip-path="url(#clip3_1551_15192)">
<path d="M79.9987 1.33398L82.0587 5.50732L86.6654 6.18065L83.332 9.42732L84.1187 14.014L79.9987 11.8473L75.8787 14.014L76.6654 9.42732L73.332 6.18065L77.9387 5.50732L79.9987 1.33398Z" fill="#A6A6A6" stroke="#A6A6A6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<g clip-path="url(#clip4_1551_15192)">
<path d="M103.999 1.33398L106.059 5.50732L110.665 6.18065L107.332 9.42732L108.119 14.014L103.999 11.8473L99.8787 14.014L100.665 9.42732L97.332 6.18065L101.939 5.50732L103.999 1.33398Z" fill="#A6A6A6" stroke="#A6A6A6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</g>
<defs>
<clipPath id="clip0_1551_15192">
<rect width="16" height="16" fill="white"/>
</clipPath>
<clipPath id="clip1_1551_15192">
<rect width="16" height="16" fill="white" transform="translate(24)"/>
</clipPath>
<clipPath id="clip2_1551_15192">
<rect width="16" height="16" fill="white" transform="translate(48)"/>
</clipPath>
<clipPath id="clip3_1551_15192">
<rect width="16" height="16" fill="white" transform="translate(72)"/>
</clipPath>
<clipPath id="clip4_1551_15192">
<rect width="16" height="16" fill="white" transform="translate(96)"/>
</clipPath>
</defs>
</svg>
<div class="flex flex-col gap-3 w-full sm:w-[70%]">
    <label for="" class="text-[14px]">Your Name</label>
    <input type="text " class="border h-[44px]">
</div>
<div class="flex flex-col gap-3 w-full sm:w-[70%]">
    <label for="" class="text-[14px]">Your Review</label>
    <textarea placeholder="Type your text here" class="border h-[110px]"></textarea>
    
</div> -->
                        </div>

                        <button
                            class="block mx-auto w-max mt-[60px] py-[11px] px-[43px] bg-white border border-navyBlue rounded-[18px] text-center text-navyBlue text-base font-medium"
                            v-if="links?.next"
                            @click="get()"
                        >
                            @lang('shop::app.products.view.reviews.load-more')
                        </button>
                    </template>

                    <template v-else>
                      <!-- empty review div removed -->
                        <!-- <div class="grid items-center justify-items-center w-[100%] m-auto h-[476px] place-content-center text-center">
                            <img class="" src="{{ bagisto_asset('images/review.png') }}" alt="" title="">

                            <p class="text-[20px]">
                                @lang('shop::app.products.view.reviews.empty-review')
                            </p>
                        </div> -->
                    </template>
                </template>
            </div>
        </div>
    </script>

    {{-- Product Review Item Template --}}
    <script type="text/x-template" id="v-product-review-item-template">
        <div class="flex justify-between w-full  rounded-[12px] max-sm:flex-wrap max-xl:mb-[20px]">
            <div>
                <!-- <div
                    class="flex justify-center items-center min-h-[100px] max-h-[100px] min-w-[100px] max-w-[100px] rounded-[12px] bg-[#F5F5F5] max-sm:hidden"
                    :title="review.name"
                >
                    <span
                        class="text-[24px] text-[#6E6E6E] font-semibold"
                        v-text="review.name.split(' ').map(name => name.charAt(0).toUpperCase()).join('')"
                    >
                    </span>
                </div> -->
            </div>

            <div class="w-full">
                <div class="">
                    <p
                        class="text-[20px] font-medium max-sm:text-[16px]"
                        v-text="review.name"
                    >
                    </p>
                    <p
                    class=" text-[14px] font-medium max-sm:text-[12px] w-[375px]"
                    v-text="review.created_at"
                >
                </p>
                   
                </div>

                
                <div class="flex items-center mb-[10px]">
                        <x-shop::products.star-rating 
                            ::name="review.name" 
                            ::value="review.rating"
                        >
                        </x-shop::products.star-rating>
                    </div>
                <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae, necessitatibus molestias. Eaque at accusantium doloribus explicabo tempora modi id, quas corrupti obcaecati nemo animi odio amet sequi libero dolore possimus nam qui eum, sunt veritatis quo cupiditate delectus vero tenetur! Soluta veniam doloribus sunt repudiandae voluptatibus aspernatur unde praesentium consequuntur!</p>

                <!-- <p
                    class="mt-[20px] text-[16px] text-[#6E6E6E] font-semibold max-sm:text-[12px]"
                    v-text="review.title"
                >
                </p> -->

                <p
                    class="mt-[20px] text-[16px] text-[#6E6E6E] max-sm:text-[12px]"
                    v-text="review.comment"
                >
                </p>

                <div class="flex gap-2 flex-wrap mt-2">
                    <!-- <template v-for="file in review.images">
                        <a
                            :href="file.url"
                            class="h-12 w-12 flex"
                            target="_blank"
                            v-if="file.type == 'image'"
                        >
                            <img
                                class="min-w-[50px] max-h-[50px] rounded-[12px] cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                        </a>

                        <a
                            :href="file.url"
                            class="flex h-12 w-12"
                            target="_blank"
                            v-else
                        >
                            <video
                                class="min-w-[50px] max-h-[50px] rounded-[12px] cursor-pointer"
                                :src="file.url"
                                :alt="review.name"
                                :title="review.name"
                            >
                            </video>
                        </a>
                    </template> -->
                </div>
            </div>
            
        </div>
    </script>

    <script type="module">
        app.component('v-product-review', {
            template: '#v-product-review-template',

            props: ['productId'],

            data() {
                return {
                    isLoading: true,

                    canReview: true,

                    reviews: [],

                    links: {
                        next: '{{ route('shop.api.products.reviews.index', $product->id) }}',
                    },

                    meta: {},
                }
            },

            mounted() {
                this.get();
            },

            methods: {
                get() {
                    if (this.links?.next) {
                        this.$axios.get(this.links.next)
                            .then(response => {
                                this.isLoading = false;

                                this.reviews = [...this.reviews, ...response.data.data];

                                this.links = response.data.links;

                                this.meta = response.data.meta;
                            })
                            .catch(error => {});
                    }
                },

                store(params, { resetForm, setErrors }) {
                    this.$axios.post('{{ route('shop.api.products.reviews.store', $product->id) }}', params, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            resetForm();

                            this.canReview = false;
                        })
                        .catch(error => {
                            setErrors({'attachments': ["@lang('shop::app.products.view.reviews.failed-to-upload')"]});

                            this.$refs.reviewImages.uploadedFiles.forEach(element => {
                                setTimeout(() => {
                                    this.$refs.reviewImages.removeFile();
                                }, 0);
                            });
                        });
                },

                selectReviewImage() {
                    this.reviewImage = event.target.files[0];
                },
            },
        });
        
        app.component('v-product-review-item', {
            template: '#v-product-review-item-template',

            props: ['review'],
        });
    </script>
@endPushOnce