<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <calendar-view
            :show-date="showDate"
            class="theme-default holiday-us-traditional holiday-us-official">
            <calendar-view-header
                slot="header"
                slot-scope="t"
                :header-props="t.headerProps"
                @input="setShowDate" />
        </calendar-view>
            </div>
        </div>
    </div>
</template>
<script>
    import { CalendarView, CalendarViewHeader } from "vue-simple-calendar"
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        components: {
            CalendarView,
            CalendarViewHeader,
        },
        methods: {
            setShowDate(d) {
                this.showDate = d;
            },
        }
    }
</script>
<style>
</style>