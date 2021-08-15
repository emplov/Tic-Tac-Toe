<template>
    <div>
        <div>
            <div class="row">
                <div class="col-md-12">
                    <a href="/" class="btn btn-primary">
                        Back
                    </a>
                    <button class="btn btn-danger" v-if="not_fulled_in" @click="reset">
                        Reset
                    </button>
                </div>
            </div>
            <div class="text-center" v-if="not_fulled_in">
                {{ users_count }} / 2
            </div>
            <div class="text-center" v-if="not_fulled_in">
                <div v-if="turn === 'o' || turn === 'x'">
                    Turn for: {{ turn.toUpperCase() }}
                </div>
            </div>
            <div class="text-center" v-if="game_finished">
                Winner is {{ winner.user.name }}!
            </div>
            <div class="tictactoe" v-if="not_fulled_in">
                <div class="block">
                    <div class="element" @click="sendClick(1)">
                        <div class="item" v-if="items.item_1.selected">
                            <div :class="'item-' + items.item_1.turn"></div>
                        </div>
                    </div>
                    <div class="element" @click="sendClick(2)">
                        <div class="item" v-if="items.item_2.selected">
                            <div :class="'item-' + items.item_2.turn"></div>
                        </div>
                    </div>
                    <div class="element" @click="sendClick(3)">
                        <div class="item" v-if="items.item_3.selected">
                            <div :class="'item-' + items.item_3.turn"></div>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="element" @click="sendClick(4)">
                        <div class="item" v-if="items.item_4.selected">
                            <div :class="'item-' + items.item_4.turn"></div>
                        </div>
                    </div>
                    <div class="element" @click="sendClick(5)">
                        <div class="item" v-if="items.item_5.selected">
                            <div :class="'item-' + items.item_5.turn"></div>
                        </div>
                    </div>
                    <div class="element" @click="sendClick(6)">
                        <div class="item" v-if="items.item_6.selected">
                            <div :class="'item-' + items.item_6.turn"></div>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="element" @click="sendClick(7)">
                        <div class="item" v-if="items.item_7.selected">
                            <div :class="'item-' + items.item_7.turn"></div>
                        </div>
                    </div>
                    <div class="element" @click="sendClick(8)">
                        <div class="item" v-if="items.item_8.selected">
                            <div :class="'item-' + items.item_8.turn"></div>
                        </div>
                    </div>
                    <div class="element" @click="sendClick(9)">
                        <div class="item" v-if="items.item_9.selected">
                            <div :class="'item-' + items.item_9.turn"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
    name: 'Game',

    data: () => ({
        room_id: 0,
        users_count: 1,
        not_fulled_in: true,
        turn: 'x',
        game_finished: false,
        winner: {},
        items: {
            item_1: {
                turn: 'x',
                selected: false
            },
            item_2: {
                turn: 'x',
                selected: false
            },
            item_3: {
                turn: 'x',
                selected: false
            },
            item_4: {
                turn: 'x',
                selected: false
            },
            item_5: {
                turn: 'x',
                selected: false
            },
            item_6: {
                turn: 'x',
                selected: false
            },
            item_7: {
                turn: 'x',
                selected: false
            },
            item_8: {
                turn: 'x',
                selected: false
            },
            item_9: {
                turn: 'x',
                selected: false
            },
        }
    }),

    mounted() {
        const room_id = window.config.room_id
        this.room_id = room_id

        const game = window.config.game;

        if (game.moves) {
            game.moves.forEach((el) => {
                if (el.user_id === window.config.room.user_1_id) {
                    this.items['item_' + el.position].selected = true
                    this.items['item_' + el.position].turn = 'x'
                    this.turn = 'o'
                }
                if (el.user_id === window.config.room.user_2_id) {
                    this.items['item_' + el.position].selected = true
                    this.items['item_' + el.position].turn = 'o'
                    this.turn = 'x'
                }
            })
        }

        axios.post('/room/' + room_id + '/join').then((e) => {
            this.not_fulled_in = true
            window.Echo.join('room.' + room_id)
                .here((users) => {
                    this.users_count = users.length
                })
                .joining((user) => {
                    this.users_count = this.users_count + 1
                })
                .leaving((user) => {
                    this.users_count = this.users_count - 1
                })
                .error((error) => {
                    console.error(error);
                });

            window.Echo.private('room-deleted.' + room_id)
                .listen('RoomDeleted', (e) => {
                    this.not_fulled_in = false
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'warning',
                        title: 'Room has been deleted.'
                    })
                })

            window.Echo.private('moved.' + room_id)
                .listen('MoveMoved', (e) => {
                    if (parseInt(e.user_id) !== parseInt(window.config.user.id)) {
                        this.items['item_' + e.block_id].selected = true
                        this.items['item_' + e.block_id].turn = e.turn
                        if (e.turn === 'x') {
                            this.turn = 'o'
                        }
                        else {
                            this.turn = 'x'
                        }
                        this.checkGame(e.user)
                    }
                })

            window.Echo.private('reseted.' + room_id)
                .listen('Reseted', (e) => {
                    this.reset(false)
                })
        }).catch((e) => {
            this.not_fulled_in = false
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'warning',
                title: 'Room already fulled in.'
            })
            // window.location.href = '/'
        })
    },

    beforeDestroy() {
        window.Echo.leave('room.' + this.room_id)
    },

    methods: {
        async sendClick(block_id) {
            if (
                (window.config.user.id === window.config.room.user_1_id && this.turn === 'x') ||
                (window.config.user.id === window.config.room.user_2_id && this.turn === 'o')
            ) {
                if (!this.items['item_' + block_id].selected) {
                    await axios.post('/move/' + block_id + '/room/' + this.room_id, {
                        turn: this.turn,
                    })

                    this.items['item_' + block_id].selected = true
                    this.items['item_' + block_id].turn = this.turn

                    if (this.turn === 'x') {
                        this.turn = 'o'
                    } else {
                        this.turn = 'x'
                    }
                }
                this.checkGame(window.config.user)
            }
        },
        async reset(is_reseted) {
            if (is_reseted) {
                await axios.post('/reset/' + this.room_id)
            }
            for (const [key, value] of Object.entries(this.items)) {
                this.items[key].selected = false
            }

            this.turn = 'x'
        },
        checkGame(user) {
            var from_to = [
                [1, 3],
                [4, 6],
                [7, 9],
            ]
            var a = 0

            from_to.forEach((el) => {
                for (var i=el[0]; i<=el[1]; i++) {
                    if (this.items['item_' + i].selected) {
                        if (this.items['item_' + i].turn === 'x') {
                            a++;
                        }
                    }
                }

                if (a === 3) {
                    this.winner.user = user
                    this.game_finished = true
                    this.turn = 'i';
                }

                a = 0;
            })
        }
    }
}
</script>

<style>
.tictactoe {
    width: 302px;
    height: 302px;
    background: red;
    border: 2px solid black;
}

.tictactoe .block {
    width: 100%;
    height: 100px;
    display: flex;
    cursor: pointer;
}

.tictactoe .block .element {
    width: 33.3%;
    height: 100%;
    border: 1px solid black;
    display: flex;
    justify-content: center;
    align-items: center;
}

.tictactoe .block .element .item{
    width: 80%;
    height: 80%;
}

.tictactoe .block .element .item .item-x{
    width: 100%;
    height: 100%;
    background-size: cover;
    background-image: url("/images/x.png");
}

.tictactoe .block .element .item .item-o{
    width: 100%;
    height: 100%;
    background-size: cover;
    background-image: url("/images/o.png");
}
</style>
