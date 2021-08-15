<template>
    <ul class="list-group">
        <template v-for="room in rooms">
            <li class="list-group-item d-flex justify-content-between align-items-center" :key="'room_' + room.id">
                <a :href="'/room/' + room.id" class="btn btn-primary">
                    {{ room.name }}
                </a>
                <button
                    v-if="room.user_1.id === user.id || user.id_admin"
                    class="btn btn-danger"
                    @click.prevent="removeRoom(room.id)"
                >
                    <i class="fas fa-trash-alt"></i>
                </button>
            </li>
        </template>
        <li class="list-group-item text-center">
            <button class="btn btn-success" @click.prevent="createRoom">
                Create room
            </button>
        </li>
    </ul>
</template>

<script>
import axios from 'axios'

export default {
    name: 'Rooms',

    data: () => ({
        rooms: [],
        rooms_users: [],
        user: 0,
    }),

    mounted() {
        console.log(window.config.rooms)
        this.rooms = window.config.rooms
        this.user = window.config.user

        window.Echo.private('room-created')
            .listen('RoomCreated', (e) => {
                this.rooms.push(e.room)
            })

        window.Echo.private('room-deleted')
            .listen('RoomDeleted', (e) => {
                this.rooms = this.rooms.filter((el) => {
                    return parseInt(el.id) !== parseInt(e.room_id)
                })
            })
    },

    beforeDestroy() {
        window.Echo.leaveChannel('room-created')
        window.Echo.leaveChannel('room-deleted')
    },

    methods: {
        async createRoom() {
            const { data } = await axios.post('/rooms')
        },
        async removeRoom(room_id) {
            const data = await axios.post('/rooms/' + room_id + '/delete')

        }
    }
}
</script>
