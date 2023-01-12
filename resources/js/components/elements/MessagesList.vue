<template>
    <table class="table table-hover">
        <tbody>
            <tr v-for="message in messages" :key="message.id" :message="message">
                <td>
                    <router-link v-if="getUri(message.subject) == 'kh15Show'" :to="{ name: getUri(message.subject), params: { id: getId(message.subject) }}">
                        {{message.latestMessage.body}}<br>
                        <span class="badge badge-info"> {{ message.latestMessage.created_at | myDateN }} </span>
                    </router-link>
                    <router-link v-if="getUri(message.subject) == 'ttkhachtiemnang'" :to="{ name: getUri(message.subject), query : { id: message.contact }}">
                        {{message.latestMessage.body}}<br>
                        <span class="badge badge-info"> {{ message.latestMessage.created_at | myDateN }} </span>
                    </router-link>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    props: ['messages'],
    methods: {
        getUri(name){
            if (name.startsWith('KH15')) {
                return 'kh15Show';
            }
            else{
                return 'ttkhachtiemnang';
            }
        },
        getId(name){
            return name.split("#")[1];
        }
    }
}

</script>
