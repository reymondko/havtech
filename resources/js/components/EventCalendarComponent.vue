<template>
    <div class="havtech-blue-section" >
        <div class="triangle-deco"><img src="/eventshub/images/Triangle.svg" alt="" class="orange-triangle"></div>
        <div class="main-container">
            <div class="heading-row">
                <h2 class="heading-2 white">Havtech Events</h2>
                <div class="calendar-nav">
                    <a :class="getFilterClass(99)" @click="filterEvent(99)" v-if="user==1">
                        <img src="/eventshub/images/havtech_swoosh_small.svg" alt="" class="swoosh-icon"><div class="text-block-2" style="display:inline-block">My Events</div>
                    </a>
                    <a :class="getFilterClass(2)" @click="filterEvent(2)">
                        <div class="text-block-2">Special</div>
                    </a>
                    <a :class="getFilterClass(1)" @click="filterEvent(1)">
                        <div class="text-block-2">General</div>
                    </a>
                    <a :class="getFilterClass(3)" @click="filterEvent(3)">
                        <div class="text-block-2">Learning Institute</div>
                    </a>
                    <a :class="getFilterClass(0)" @click="filterEvent(0)">
                        <div class="text-block-2">All</div>
                    </a>
                </div>
            </div>
            <div class="calendar-container">
                <div class="calendar" :class="{ 'loading' : loading}">
                    <div id="calendar_section">
                        <calendar-view :show-date="showDate" :events="events" class="theme-default">
                            <calendar-view-header slot="header" slot-scope="t" :header-props="t.headerProps" @input="setShowDate" />
                        </calendar-view>
                    </div>
                </div>
                <div class="calendar-events" :class="{ 'loading' : loading}">
                    <div class="events-scroll-section">

                        <a class="events-link w-inline-block" v-for="(event,index) in events" v-bind:key="index" v-bind:href="event.url">
                            
                            <div :class="getEventCardClass(event.type_id,event.display)"  style="display:" >
                                <div class="event-category-row"><img src="/eventshub/images/Star.svg" alt="Special Event" class="special-star" v-if="event.type_id == 2">
                                    <div class="event-category">{{event.type}} </div>
                                </div>
                                <h4 class="calendar-event-name">{{event.event_name}}</h4>
                                <div class="calendar-event-date">{{event.startDateFormatted}}</div>
                                <!--<div class="calendar-event-description" v-html="event.description_raw"></div>!-->
                                <br>
                            </div>
                        </a>
                    </div>
                    <a href="#" class="scroll-arrow-link w-inline-block"></a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { CalendarView, CalendarViewHeader } from "vue-simple-calendar"
    const axios = require('axios');
    // The next two lines are processed by webpack. If you're using the component without webpack compilation,
    // you should just create <link> elements for these. Both are optional, you can create your own theme if you prefer.
    require("vue-simple-calendar/static/css/default.css")
    require("vue-simple-calendar/static/css/holidays-us.css")
 
    export default {
        props: ['eventsddata'],
        name: 'app',
        data: function() {
            return {
                // Set initial date for calendar 
                showDate: new Date(),
                
                // Selected Year and Month 
                // on the Calendar (Y-m) format
                selected_period:null, 
                // filter for the event type 
                // 0 = All
                // 1 = General Events
                // 2 = Special Events
                // 3 = LI Events
                filter:0,
                //Loader
                loading:false,
                events:[],
                user:null
             }
        },
        mounted() {
           this.events = this.eventsddata;
           this.selected_period = this.showDate.getFullYear() + '-' + (this.showDate.getMonth() +1);
           this.getUser();
        },
        components: {
            CalendarView,
            CalendarViewHeader,
        },
        methods: {
            // Sets current date to now
            setShowDate(d) {
                this.showDate = d;
            },
            getUser(){
                axios.get('/user').then(response => {
                    console.log(response.data);
                    if(response.data){
                        this.user=1;
                    }
                    else{
                         this.user=null;
                    }
                })
            },

            // Updates the current events 
            // based on the filter and period values
            updateEvents(){
                console.log(this.selected_period);
                console.log(this.filter);
                this.loading = true;
                var t = this;
                axios.post('/events/filter', {
                    'period':this.selected_period,
                    'filter':this.filter
                })
                .then(response => {
                    if(response.data.status == 'success'){
                        t.events = response.data.data.events
                    }
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                });
            },

            // Retrieves Classe for the filters
            getFilterClass(num){
                var elem_class = "nav-all-events w-inline-block";
                if(this.loading){
                    elem_class += ' loading';
                }
                if(num === this.filter){
                    elem_class += ' active';
                }
                return elem_class;
            },

            getEventCardClass(num,display){
                if(num == 1 && display== "block"){
                    return 'general-event';
                }
                if(num == 2 && display== "block"){
                    return 'special-event';
                }
                if(num == 3 && display== "block"){
                    return 'learning-institute';
                }
                if(num == 1 && display== "hidden"){
                    return 'general-event hide-event';
                }
                if(num == 2 && display== "hidden"){
                    return 'special-event hide-event';
                }
                if(num == 3 && display== "hidden"){
                    return 'learning-institute hide-event';
                }
            },


            filterEvent(num){
                this.filter = num;
                this.updateEvents();
            }
        },
         watch: {
            // Watches changes in date value
            showDate: {
                handler: function(val, oldVal) {
                    if(val != oldVal){
                         this.selected_period = val.getFullYear() + '-' + (val.getMonth() +1);
                         this.updateEvents();
                    }
                },
                deep: true
            }
        }
    }
</script>
<style>
    #calendar_section {
        font-family: titling-gothic-fb-wide, sans-serif;
        color: #2c3e50;
        height: 67vh;
        margin-left: auto;
        margin-right: auto;
        max-width: 880px;
        max-height: 670px;
    }

    .cv-wrapper {
        max-width: 880px;
        max-height: 670px;
    }
    .theme-default 
    .cv-header 
    .periodLabel {
        font-size: 13px !important;
        font-weight: 500;
        position: absolute;
        margin-left: 23px;
        margin-top: 0px;
        width: 180px !important;
        text-align: center;
        display: block;
        text-transform: uppercase;
        color: #626262;
        left: 10px;
        top:8px;
    }

    .theme-default 
    .cv-header button {
        font-family: titling-gothic-fb-wide, sans-serif;
        font-size: 12px;
        font-weight: 500;
        text-align: center;
        text-transform: uppercase;
        line-height: unset;
        border: none;
        outline: none;
    }

     .theme-default 
    .cv-header button:hover{
        color:#fca327; 
    }

    #calendar_section > div > div.cv-header > div.cv-header-nav > button.currentPeriod{
        opacity: 0 !important;
    }

    #calendar_section > div > div.cv-header > div.cv-header-nav > button.nextPeriod{
        margin-left: 60px;
    }

    .cv-header-nav .previousYear,
    .cv-header-nav .nextYear{
        display: none;
    }

    .theme-default .outsideOfMonth  {
        color: transparent;
    }

    .cv-day, 
    .cv-event, 
    .cv-header-day, 
    .cv-header-days, 
    .cv-week, 
    .cv-weeks{
        border:none;
        background: white !important;
        text-transform: uppercase;
        color: #626262;
    }

   .theme-default .cv-day {
      background: white !important;
    }

    .cv-header, .cv-header button {
        background: white !important;
        border:none;
    }

    .cv-header-days{
        margin-bottom: 70px;
    }

    .theme-default .cv-day{
        background-color: #fafafa;
        text-align: center;
        display: block;
        font-size: 12px;
        line-height: 0px;
        font-weight: 500;
        padding-top: 15px;
    }

    .cv-header{
        margin-bottom: 20px;
        position: relative;
    }

    .cv-week>div:first-child,
    .cv-header-days>div:first-child{
        opacity: 0.4;
    }

    .cv-week>div:last-child,
    .cv-header-days>div:last-child{
        opacity: 0.4;
    }

    .ge-ci{
        text-align: center;
        position: absolute;
        top: 0 !important;
        color: white;
        display: block;
        border: white 1px solid;
        opacity: 1 !important;
        background-image: linear-gradient(90deg, #003c78, #0057ac) !important;
        width: 40px !important;
        margin-left: 35px;
        height: 40px;
        padding-top: 15px;
        border-radius: 40px !important;
        font-size: 12px !important;
    }

    .se-ci{
        text-align: center;
        position: absolute;
        top: 0 !important;
        color: white;
        display: block;
        border: white 1px solid;
        opacity: 1 !important;
        background-image: linear-gradient(90deg, #fca327, #ed7000) !important;
        width: 40px !important;
        margin-left: 35px;
        height: 40px;
        padding-top: 15px;
        border-radius: 40px !important;
        font-size: 12px !important;
    }

    .li-ci{
        text-align: center;
        position: absolute;
        top: 0 !important;
        color: white;
        display: block;
        border: white 1px solid;
        opacity: 1 !important;
        background-image: linear-gradient(90deg, #797979, #626262) !important;
        width: 40px !important;
        margin-left: 35px;
        height: 40px;
        padding-top: 15px;
        border-radius: 40px !important;
        font-size: 12px !important;
    }
    .nav-all-events.w-inline-block{
        cursor: pointer;
    }
    #calendar_section > div > div.cv-header > div.cv-header-nav > button.currentPeriod{
        opacity: 0;
    }
    .loading{
        opacity: 0.4;
        pointer-events: none;
    }
    .hide-event{
        display: none;
    }
</style>