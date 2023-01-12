<template>
    <ul class="list-group list-group-flush">
        <li class="list-group-item" v-for="item in items" :key="item.id"> {{item.name}} <small>{{item.description}}</small></li>
    </ul>
</template>
<script>
export default {
    props: ['items']
}

</script>
