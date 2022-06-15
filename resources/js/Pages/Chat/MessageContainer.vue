<template>
    <div class="h-96 w-full">
        <div class="h-full p-2 flex flex-col overflow-scroll">
         <div v-for="(message,index) in messages" :key="index">
             <mess.age-item :message="message"/>
         </div>
        </div>
    </div>
</template>

<script>
import MessageItem from "./MessageItem";
export default {
    components: {MessageItem},
    props: ['messages'],
    computed:{
        messages(){
            return this.messages.slice().reverse();
        }
    }
}
</script>

<style scoped>

</style>
