import { Component, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { UserRights } from 'src/app/models/user-rights.model';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { AuthService } from 'src/app/services/auth.service';
import { ParamsService } from 'src/app/services/params.service';
import { RoomtypeService } from 'src/app/services/roomtype.service';

@Component({
  selector: 'app-roomcosts',
  templateUrl: './roomcosts.component.html',
  styleUrls: ['./roomcosts.component.css']
})
export class RoomcostsComponent implements OnInit{
  roomTypes:any;
  feddback_message_status: any;
  msg: any;
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  feedback_message: any;
periods:any;
roomTypeCost:any;
myrights=new UserRights();

  constructor(

    private authservice:AuthService,
    private roomtypeservice: RoomtypeService,
    private activeperiodservice:ActiveperiodsService,
    private params:ParamsService
  ) {}
  ngOnInit(): void {
    this.getroomtypeCosts("max");
    this.getroomtypes();
    this.getPeriods();
    this.myrights=this.params.getparam('myrights');
  }


  setRoomTypeCost(form:any)
  {
    this.feddback_message_status = 0;
    this.feedback_message = "";
   form.value.set_by= this.authservice.getUserId();
   console.log(form.value);

    this.roomtypeservice.setRoomcost(form.value,{headers:this.authservice.getHeaders()}).subscribe(res=>{
      this.feddback_message_status=1;
      this.msg = res;
      this.feedback_message=this.msg.message;
      // this.roomTypes=this.msg.roomtypes;
      this.roomTypeCost=this.msg.roomcost

    },error=>{
      this.feddback_message_status=2;
      this.feedback_message=error.error.message;
    });
  }
  filter(period_id:any)
  {
console.log(period_id.value);

  }
  getroomtypeCosts(active_period_id:any){


    console.log(active_period_id);

    this.feddback_message_status = 0;
    this.feedback_message = "";
    this.roomtypeservice.getRoomcost({active_period_id:active_period_id},{headers:this.authservice.getHeaders()}).subscribe(res=>{
this.roomTypeCost=res;
console.log(res);


    },error=>{
      this.feddback_message_status = 2;
          console.log(error);

    })
  }
  getroomtypes(){
    this.feddback_message_status = 0;
    this.feedback_message = "";
this.roomtypeservice.getRoomType({headers:this.authservice.getHeaders()}).subscribe(res=>{
this.roomTypes=res;

},error=>{
  this.feddback_message_status = 2;
        this.feedback_message = "server error";
})
  }
  getPeriods(){
    this.feddback_message_status = 0;
    this.feedback_message = "";
this.activeperiodservice.getActivePeriod({headers:this.authservice.getHeaders()}).subscribe(res=>{
this.periods=res;

},error=>{
  this.feddback_message_status = 2;
        this.feedback_message = "server error";
})
  }
}
