<template>
    <div>
        <span v-for="product in getName(items)" class="badge badge-success">{{product}}</span>
    </div>
</template>
<script>
export default {
    props: ['items'],
    methods: {
        getName(values) {
            if (!values) return ''
            return values.map((item, index, values) => {
                return item.name
            })
        }
    }
}

</script>
