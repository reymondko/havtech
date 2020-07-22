<?php
 //create event overview
 public function addEventSchedule(Request $request)
 {
     //loop store to event schedule db
     $y=1;
     for($x=0;$x < $request->eventsched_cnt;$x++){
         //$date=explode(" - ",$request->start_dateonly[$x]);
         $date = $request->start_dateonly[$x];
         $start_date = date("Y-m-d H:i:s",strtotime($date." ".$request->start_time[$x]));
         $end_date = date("Y-m-d H:i:s",strtotime($date." ".$request->end_time[$x]));    
         
         $eventsched= new EventSchedule;
         //echo $request->title[$x]." - ".$start_date." - ". $end_date." ==zz " ;
         
         //upload banner image
         //check if there is banner image
         if($request->hasFile('banner_images.'.$x)) { 
             $file = $request->file('banner_images.'.$x);
             $name = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
             $image['filePath'] = $name;
             $file->move(public_path().'/uploads/', $name);
             $eventsched->image= '/uploads/'. $name; //public_path().         
         }
         $thumbnames=" ";
         //check if there is thumb image
         if($request->hasFile('thumb_images.'.$x)) { 
             $file = $request->file('thumb_images.'.$x);
             $thumbname = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
             $image['filePath'] = $thumbname;
             $file->move(public_path().'/uploads/', $thumbname);
             $eventsched->thumb_image= '/uploads/'. $thumbname; //public_path().
             $thumbname.="   --  ".$thumbname;         
         }
         $eventsched->event_id= $request->event_id;
         $eventsched->title = $request->title[$x];
         $eventsched->start_date = $start_date;
         $eventsched->end_date = $end_date;
         $eventsched->location = $request->location[$x];
         $eventsched->location_address = $request->location_address[$x];
         $eventsched->location_address2 = $request->location_address2[$x];
         $eventsched->city = $request->city[$x];
         $eventsched->state = $request->state[$x];
         $eventsched->zip = $request->zip[$x];
         $eventsched->description = $request->description[$x];
         $eventsched->directions_button = $request->input("directions_button".$y);
         $eventsched->website_url = $request->website_url[$x];
         $eventsched->save();
         $sched_ids[]=$eventsched->id;
         $y++;
     } 
     $nextstep= $request->step;
     return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id); 
     //return Response::json(array('success' => true, 'sched_ids' => $sched_ids, 'event_id' => $request->event_id), 200);
     //return "success ".$thumbname;     
     return Response::json(array('success' => true, 'next' => $next), 200);      
 }
//create event Dining
    public function addEventDining(Request $request)
    {  
        $y=1;
        for($x=0;$x < $request->form_cnt;$x++){
            $eventdining= new EventDining;
            //upload banner image
            //check if there is banner image
            if($request->hasFile('banner_images.'.$x)) { 
                $file = $request->file('banner_images.'.$x);
                $name = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $name;
                $file->move(public_path().'/uploads/', $name);
                $eventdining->image= '/uploads/'. $name; //public_path().         
            }
           
            //for directions_button
            //$directions_button = $request->directions_button.$x;
            $eventdining->event_id= $request->event_id;
            $eventdining->dining_type = $request->dining_type[$x];
            $eventdining->location = $request->location[$x];
            $eventdining->meal_date = $request->meal_date[$x];
            $eventdining->start_time = $request->start_time[$x];
            $eventdining->end_time = $request->end_time[$x];
            $eventdining->address1 = $request->address1[$x];
            $eventdining->address2 = $request->address2[$x];
            $eventdining->city = $request->city[$x];
            $eventdining->state = $request->state[$x];
            $eventdining->zip = $request->zip[$x];
            $eventdining->phone = $request->phone[$x];
            $eventdining->description = $request->description[$x];
            $eventdining->directions_button = $request->input("directions_button".$y);
            $eventdining->website_url = $request->website_url[$x];
            $eventdining->save();
            $y++;
        }        
        $nextstep= $request->step;
        return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id);     
        //return Response::json(array('success' => true, 'acc_ids' => $acc_ids, 'event_id' => $request->event_id), 200);
        //return "success ".$thumbname;           
    }

    public function addEventTransportation(Request $request)
    {        
        $eventtrans = new EventTransportation;
        $eventtrans->event_id = $request->event_id;
        $eventtrans->company_name = $request->company_name;
        $eventtrans->service_address1 = $request->service_address1;        
        $eventtrans->service_address2 = $request->service_address2;
        $eventtrans->address1 = $request->address1;
        $eventtrans->address2 = $request->address2;
        $eventtrans->city = $request->city;
        $eventtrans->state = $request->state;
        $eventtrans->zip = $request->zip;
        $eventtrans->phone = $request->phone;
        $eventtrans->description = $request->description;
        $eventtrans->directions_button = $request->directions_button;
        $eventtrans->website_url = $request->website_url;
        $eventtrans->flight_description = $request->flight_description;
        
        if($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');

            $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

            $image['filePath'] = $name;
            $file->move(public_path().'/uploads/', $name);
            $eventtrans->image= '/uploads/'. $name; //public_path().            
        }
        if($eventtrans->save()){      
            $nextstep= $request->step;
            return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id);
            //return Response::json(array('success' => true, 'event_id' => $eventtrans->id), 200);
        }
        else{
            return "failed";
        }        
    }

    //create event Accomodations
    public function addAccomodations(Request $request)
    {  
        $y=1;
        for($x=0;$x < $request->accomodations_cnt;$x++){
           
            $eventaccomodations= new EventAccomodations;
            //upload banner image
            //check if there is banner image
            if($request->hasFile('banner_images.'.$x)) { 
                $file = $request->file('banner_images.'.$x);
                $name = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $name;
                $file->move(public_path().'/uploads/', $name);
                $eventaccomodations->image= '/uploads/'. $name; //public_path().         
            }
            $eventaccomodations->event_id= $request->event_id;
            $eventaccomodations->hotel = $request->hotel[$x];
            $eventaccomodations->name = $request->name[$x];
            $eventaccomodations->confirmation_number = $request->confirmation_number[$x];
            $eventaccomodations->room_number = $request->room_number[$x];
            $eventaccomodations->location = $request->location[$x];
            $eventaccomodations->address1 = $request->location_address[$x];
            $eventaccomodations->address2 = $request->location_address2[$x];
            $eventaccomodations->city = $request->city[$x];
            $eventaccomodations->state = $request->state[$x];
            $eventaccomodations->zip = $request->zip[$x];
            $eventaccomodations->phone = $request->phone[$x];
            $eventaccomodations->description = $request->description[$x];
            $eventaccomodations->directions_button = $request->input("directions_button".$y);
            $eventaccomodations->website_url = $request->website_url[$x];
            $eventaccomodations->save();
            $acc_ids[]=$eventaccomodations->id;
            $y++;
        }
        $nextstep= $request->step;
        return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id);        
        //return Response::json(array('success' => true, 'acc_ids' => $acc_ids, 'event_id' => $request->event_id), 200);
        //return "success ".$thumbname;           
    }
    //create event Travel host
    public function addEventTravelHost(Request $request)
    {
        $travelhosts="";
        //loop store to event travel host db
        $z=1;
        for($x=0;$x < $request->travelhost_cnt;$x++){
            $travelhost= new EventTravelHosts;
            //upload banner image
            //check if there is banner image
            if($request->hasFile('banner_images.'.$x)) { 
                $file = $request->file('banner_images.'.$x);
                $name = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $name;
                $file->move(public_path().'/uploads/', $name);
                $travelhost->image= '/uploads/'. $name; //public_path().         
            }
            $thumbnames=" ";
            //check if there is thumb image
            if($request->hasFile('thumb_images.'.$x)) { 
                $file = $request->file('thumb_images.'.$x);
                $thumbname = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $thumbname;
                $file->move(public_path().'/uploads/', $thumbname);
                $travelhost->thumb_image= '/uploads/'. $thumbname; //public_path().
                $thumbname.="   --  ".$thumbname;         
            }            
            $travelhost->event_id= $request->event_id;
            $travelhost->host_name = $request->host_name[$x];
            $travelhost->address1 = $request->address1[$x];
            $travelhost->address2 = $request->address2[$x];
            $travelhost->description = $request->description[$x];
            $travelhost->email_button =  $request->input("email_button".$z);
            $travelhost->email = $request->email[$x];
            $travelhost->save();
            $z++;
            //$travelhosts[]=$travelhost->id;
            
        }
        //$event_infos=arra;
        //upload event info file
        $a=1;
        for($y=0;$y < $request->info_cnt;$y++){
            //insert even info part
            $event_info= new EventFaqs;                            
            $event_info->event_id= $request->event_id;
            $event_info->download_link =  $request->input("download_link".$a);            
            //$event_info->event_info_file = $event_info_file;
            $event_info->faq_title = $request->faq_title[$y];
            $event_info->faq_answer = $request->faq_answer[$y];
            if($request->hasFile('info_files.'.$y)) { 
                $file = $request->file('info_files.'.$y);
                $info_file = md5($file->getClientOriginalName() . time()).'.'.$file->getClientOriginalExtension();
                $image['filePath'] = $info_file;
                $file->move(public_path().'/uploads/', $info_file);
                $travelhost->event_info_file= '/uploads/'. $info_file; 
            }   
            $event_info->save();
            $a++;
        }
        $nextstep= $request->step;
        return app('App\Http\Controllers\EventsController')->getNextstep($nextstep,$request->event_id);
        
        //return Response::json(array('success' => true, 'travelhosts' => $travelhosts,'event_info' => $event_infos, 'event_id' => $request->event_id), 200);
        //return "success ".$thumbname;           
    }