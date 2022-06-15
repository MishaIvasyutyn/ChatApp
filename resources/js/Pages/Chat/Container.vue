<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <chat-room-selection
                    v-if="currentChatRoom.id"
                    :rooms="chatRooms"
                    :currentChatRoom="currentChatRoom"
                    v-on:roomchanged="setCurrentChatRoom($event)"
                />
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <message-container :messages="messages"/>
                    <input-message :room="currentChatRoom"
                    v-on:messageSent="getMessages()"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import MessageContainer from "./MessageContainer";
import InputMessage from "./InputMessage";
import ChatRoomSelection from "./ChatRoomSelection";

export default {
    components: {
        ChatRoomSelection,
        AppLayout,
        MessageContainer,
        InputMessage
    },
    data: function () {
        return {
            chatRooms: [],
            currentChatRoom: [],
            messages: [],
        }
    },
    watch:{
        currentRoom(){
            this.connected();
        }
    },
    methods: {
        connected(){
            if (this.currentChatRoom.id){
                let vm=this;
                this.getMessages();
                window.Echo.private("chat" + this.currentChatRoom.id)
                    .listen('.message.new', e=>{
                        vm.getMessages();
                    });
            }
        },
        getRooms() {
            axios.get('/chat/rooms').then(response => {
                this.chatRooms = response.data;
                this.setCurrentChatRoom(response.data[0]);
            }).catch(error => {
                console.log(error);
            });
        },
        setCurrentChatRoom(room) {
            this.currentChatRoom = room;
        },
        getMessages() {
            axios.get('/chat/room/' + this.currentChatRoom.id + '/messages').then(response => {
                this.messages = response.data;
            }).catch(error => {
                console.log(error);
            });
        },
    },
    created() {
        this.getRooms();
    },
}
</script>
