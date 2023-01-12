<template>
	<div class="table table-hover">
		<tbody>
			 <tr v-for="message in nghipheps" :key="message.id" :message="message">
                <td>
                    <router-link v-if="getUri(message.subject) == 'nghiphep'" :to="{ name: getUri(message.subject), params: { id: getId(message.subject) }}">
                        {{message.latestMessage.body}}<br>
                        <span class="badge badge-info"> {{ message.latestMessage.created_at | myDateN }} </span>
                    </router-link>
                    <router-link v-if="getUri(message.subject) == 'quyetdinh'" :to="{ name: getUri(message.subject), query : { id: message.contact }}">
                        {{message.latestMessage.body}}<br>
                        <span class="badge badge-info"> {{ message.latestMessage.created_at | myDateN }} </span>
                    </router-link>
                </td>
            </tr>
		</tbody>
	</div>
</template>
<script>
export default {
    props: ['nghipheps'],
    methods: {
        getUri(name){
            if (name.startsWith('nghiphep')) {
                return 'nghiphep';
            }
            else{
                return 'quyetdinh';
            }
        },
        getId(name){
            return name.split("#")[1];
        }
    }
}
</script>