@props([
    'name'  => '',
    'value' => 1,
])
<div class="flex flex-col sm:flex-row sm:items-center sm:gap-[100px] gap-4">

<h1>Quantity:</h1>


    <v-quantity-changer
    {{ $attributes->merge(['class' => 'flex justify-center border  items-center h-[32px] rounded-none w-[147px] border-[#DADADA]']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    >
</v-quantity-changer>

</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-quantity-changer-template">
        
        <div>
            <span 
                class=" cursor-pointer h-[24px] " 
                @click="decrease"
            >
            -
            </span>

            <p
                class="w-[30px] text-center  select-none flex justify-center items-center border-r border-l h-[32px] border-[#DADADA]"
                v-text="quantity"
            ></p>
            
            <span 
                class=" cursor-pointer h-[24px]"
                @click="increase"
            >
            +
            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: '#v-quantity-changer-template',

            props:['name', 'value'],

            data() {
                return  {
                    quantity: this.value,
                }
            },

            watch: {
                value() {
                    this.quantity = this.value;
                },
            },

            methods: {
                increase() {
                    this.$emit('change', ++this.quantity);
                },

                decrease() {
                    if (this.quantity > 1) {
                        this.quantity -= 1;
                    }

                    this.$emit('change', this.quantity);
                },
            }
        });
    </script>
@endpushOnce
