@props(['options'])

<v-carousel>
    <div class="shimmer w-full aspect-[2.743/1]">
    </div>
</v-carousel>

@pushOnce('scripts')
    <script type="text/x-template" id="v-carousel-template">
        <div class="w-full relative m-auto">
            <a
                v-for="(image, index) in images"
                class=""
                :href="image.link || '#'"
                ref="slides"
                :key="index"
                aria-label="Image Slide "
            >
            <div class="flex flex-col sm:flex-row w-[100%] bg-[#FCE4DE] sm:pr-20" style="height:100% !important">
            <div class="sm:w-[50%] w-[100%] flex flex-col justify-center  pr-20 sm:pl-28 pl-10 gap-10 my-20 ">
                <div>
                <h1 class="text-[21px]">100% Genuine Brands</h1>
                <h1 class="text-[40px] pr-40 font-medium">Powermatte Lip Pigment</h1>
                </div>
               
                <p class="text-[14px]">The soft, lightweight, non-drying texture of Powermatte Lip Pigment offers a velvety matte finish with unbeatable comfort.</p>
                <button class="h-[54px] w-[147px] border-2 border-black font-medium text-black">Shop Now</button>
            </div>
                <x-shop::media.images.lazy
                    class="sm:w-[50%] w-[100%] aspect-[2.743/1]"
                    ::src="image.image"
                    ::srcset="image.image + ' 1920w, ' + image.image.replace('storage', 'cache/large') + ' 1280w,' + image.image.replace('storage', 'cache/medium') + ' 1024w, ' + image.image.replace('storage', 'cache/small') + ' 525w'"
                    alt=""
                ></x-shop::media.images.lazy>
                </div>
            </a>
           <div class="flex gap-3 py-6 justify-center">

           
            <div
                class=" text-[24px] font-bold text-white  w-[79px] h-[3px]  bg-[rgba(0,0,0,0.8)] transition-all   hover:bg-[#FFA68B] cursor-pointer"
                v-if="images?.length >= 2"
                @click="navigate(currentIndex -= 1)"
            >
            </div>

            <div
                class=" text-[24px] font-bold text-white w-[79px] h-[3px]    bg-[rgba(0,0,0,0.8)] transition-all  hover:bg-[#FFA68B] cursor-pointer"
                v-if="images?.length >= 2"
                @click="navigate(currentIndex += 1)"
            >
            </div>
            <div
                class=" text-[24px] font-bold text-white w-[79px] h-[3px]    bg-[rgba(0,0,0,0.8)] transition-all  hover:bg-[#FFA68B] cursor-pointer"
                v-if="images?.length >= 2"
                @click="navigate(currentIndex += 1)"
            >
            </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component("v-carousel", {
            template: '#v-carousel-template',

            data() {
                return {
                    currentIndex: 1,

                    images: @json($options['images'] ?? []),
                };
            },

            mounted() {
                this.navigate(this.currentIndex);

                this.play();
            },

            methods: {
                navigate(index) {
                    if (index > this.images.length) {
                        this.currentIndex = 1;
                    }

                    if (index < 1) {
                        this.currentIndex = this.images.length;
                    }

                    let slides = this.$refs.slides;

                    for (let i = 0; i < slides.length; i++) {
                        if (i == this.currentIndex - 1) {
                            continue;
                        }
                        
                        slides[i].style.display = 'none';
                    }

                    slides[this.currentIndex - 1].style.display = 'block';
                },

                play() {
                    let self = this;

                    setInterval(() => {
                        this.navigate(this.currentIndex += 1);
                    }, 5000);
                }
            }
        });
    </script>

    <style>
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @-webkit-keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }
    </style>
@endpushOnce