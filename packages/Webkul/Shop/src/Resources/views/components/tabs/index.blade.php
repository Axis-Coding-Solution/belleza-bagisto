@props(['position' => 'left'])

<v-tabs
    position="{{ $position }}"
    {{ $attributes }}
>
    <x-shop::shimmer.tabs/>
</v-tabs>

@pushOnce('scripts')
    <script type="text/x-template" id="v-tabs-template">
        <div>
            <div
                class="flex gap-[30px]   bg-[white] max-1180:hidden "
                :style="positionStyles"
            >
                <div
                    v-for="tab in tabs"
                    class="pb-[18px]  text-[20px] font-medium text-[#6E6E6E] cursor-pointer"
                    :class="{'text-[#A81D46] border-[#A81D46] border-b-[2px] transition': tab.isActive }"
                    v-text="tab.title"
                    @click="change(tab)"
                >
                </div>
            </div>

            <div>
                {{ $slot }}
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-tabs', {
            template: '#v-tabs-template',

            props: ['position'],

            data() {
                return {
                    tabs: []
                }
            },

            computed: {
                positionStyles() {
                    return [
                        `justify-content: 'start'`
                    ];
                },
            },

            methods: {
                change(selectedTab) {
                    this.tabs.forEach(tab => {
                        tab.isActive = (tab.title == selectedTab.title);
                    });
                },
            },
        });
    </script>
@endPushOnce
