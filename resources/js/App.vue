<template>
    <div id="app">
        <component :is="layout || 'div'">
            <router-view />
        </component>
    </div>
</template>

<script>

    import {mapGetters} from "vuex";

    export default {
        computed: {
            layout() {
                return (this.$route.meta.layout || 'Front') + '-layout'
            }
        }
    }
</script>
