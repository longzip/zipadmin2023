<template>
    <ul class="list-unstyled">
        <li v-for="activity in activities" :key="activity.id">
            <router-link :to="{ name: getName(activity), query: { id: getId(activity) }}">
                <span class="text">{{activity.description}}</span> <small class="badge badge-secondary">{{activity.created_at | myDateN}}</small>
            </router-link>
        </li>
    </ul>
</template>
<script>
export default {
    props: ['activities'],
    methods: {
        getName(name) {
            return name.properties.zip;
        },
        getId(name) {
            return name.properties.id;
        }
    }
}

</script>
