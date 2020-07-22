<template>
    <div data-animation="slide" data-duration="700" data-infinite="1" class="slider w-slider">
      <div class="w-slider-mask" v-if="events.length > 0">
        <div class="slide-1 w-slide" style="transform: translateX(0px); opacity: 1; transition: transform 700ms ease 0s;">
          <div class="caption-container">
            <div class="caption">
              <h1 class="page-heading">Welcome to Havtech Events Hub!</h1>
              <div class="hero-caption">
                <!--Our HAVTECH EventsHUB App is a tool we created to make it easier for you to attend continuing education courses, learn about new products from manufacturers, and receive invites to special events exclusive to our customers.!-->
                Havtech recognizes the need for professionals like you to stay informed about the latest products and industry trends. Havtech Events Hub is a tool we created to make it easier for you to attend continuing education courses, learn about new products from manufacturers, and receive invites to special events exclusive to our customers.
                </div>
                <div class="slider-button-container">
                  <a href="/download-mobile-app" class="base-button orange-button w-button" data-ix="button-hover" style="transition: transform 300ms ease 0s; transform: scaleX(1) scaleY(1);">Learn More</a>
                </div>
              </div>
            </div>
        </div>
        <div class="slide-1 w-slide" v-for="(event,index) in events" v-bind:key="index"  v-bind:style="{backgroundImage: 'url(' + event.image + ')'}">
          <div  :class="getEventClassType(event)">
            <div class="caption">
              <h1 class="page-heading">{{event.event_name}}</h1>
              <div class="caption-event-date">{{event.event_date_range}}</div>
              <div class="slider-button-container">
                <a v-bind:href="event.url" class="base-button first-button w-button" data-ix="button-hover">More Info</a>
                <a v-bind:href="event.register_url" class="base-button orange-button w-button" data-ix="button-hover">Register</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="w-slider-mask" v-if="events.length == 0">
        <div class="w-slider-mask">
          
        <div class="slide-1 w-slide" style="transform: translateX(0px); opacity: 1; transition: transform 700ms ease 0s;">
          <div class="caption-container">
            <div class="caption">
              <h1 class="page-heading">Welcome To Havtech Events Hub!</h1>
              <div class="hero-caption">
                <!--Our HAVTECH EventsHUB App is a tool we created to make it easier for you to attend continuing education courses, learn about new products from manufacturers, and receive invites to special events exclusive to our customers.!-->
                Havtech recognizes the need for professionals like you to stay informed about the latest products and industry trends. Havtech Events Hub is a tool we created to make it easier for you to attend continuing education courses, learn about new products from manufacturers, and receive invites to special events exclusive to our customers.
                </div>
                <div class="slider-button-container">
                  <a href="/download-mobile-app" class="base-button orange-button w-button" data-ix="button-hover" style="transition: transform 300ms ease 0s; transform: scaleX(1) scaleY(1);">Learn More</a>
                </div>
              </div>
            </div>
        </div>
        <div class="slide-2 w-slide" style="transform: translateX(0px); opacity: 1; transition: transform 700ms ease 0s;">
          <div class="caption-container">
          <div class="caption">
            <h1 class="page-heading">Check back soon for upcoming events!</h1>
          </div>
          </div>
        </div>
        </div>
      </div>
    <div class="left-arrow w-slider-arrow-left" v-if="events.length > 0">
      <div class="icon w-icon-slider-left"></div>
    </div>
    <div class="right-arrow w-slider-arrow-right" v-if="events.length > 0">
      <div class="icon-2 w-icon-slider-right"></div>
    </div>
    <div class="w-slider-nav w-round" v-if="events.length > 0"></div>
  </div>
</template>
<script>
    export default {
        props: ['events'],
        mounted() {
            console.log('Event Slider Component Mounterd')
            console.log(this.events);
        },
        computed:{
         
        },
        methods: {
           getEventClassType(obj){
             var elem_class = "caption-container";
             if(obj.type === "Special Events"){
               elem_class += ' special';
             }

             if(obj.type === "LI Events"){
               elem_class += ' li';
             }

             if(obj.type === "Learning Institute"){
               elem_class += ' li';
             }
             return elem_class;
          }
        }
    }
</script>
<style>
</style>