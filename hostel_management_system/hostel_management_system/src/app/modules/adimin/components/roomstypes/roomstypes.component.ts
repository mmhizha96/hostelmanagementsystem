import { RoomtypeService } from './../../../../services/roomtype.service';
import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { error } from 'jquery';

import { Subject } from 'rxjs';
import { UserRights } from 'src/app/models/user-rights.model';
import { AuthService } from 'src/app/services/auth.service';
import { ParamsService } from 'src/app/services/params.service';

@Component({
  selector: 'app-roomstypes',
  templateUrl: './roomstypes.component.html',
  styleUrls: ['./roomstypes.component.css']
})
export class RoomstypesComponent implements OnInit  {
  roomTypes:any;
  feddback_message_status: any;
  msg: any;
  myrights= new UserRights();
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  feedback_message: any;
  ngOnInit(): void {
   this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,
      //  paging:false
      lengthChange: false,
      language: {
        searchPlaceholder: 'Text Customer',

      },

    };
this.myrights=this.params.getparam('myrights');
    this.getRoomTypes()
  }
  constructor(
private params:ParamsService,
    private authservice:AuthService,
    private roomtypeservice: RoomtypeService
  ) {}

  getRoomTypes(){
    this.roomtypeservice.getRoomType({headers:this.authservice.getHeaders()}).subscribe(res=>{

      this.msg = res;

      this.roomTypes=this.msg;

      this.dtTrigger.next(null);

    },error=>{
      this.feddback_message_status=2;
      this.feedback_message=error.error.message;
    });
  }

  delete(roomtype:any){
    this.feddback_message_status=0;
    this.feedback_message="";
this.roomtypeservice.deleteRoomType(roomtype,{headers:this.authservice.getHeaders()}).subscribe(res=>{
  this.feddback_message_status=1;
  this.msg = res;
  this.feedback_message=this.msg.message;
  this.roomTypes=this.msg.roomtypes;

},error=>{
  this.feddback_message_status=2;
  this.feedback_message=error.error.message;
});
  }
  createRoomTypes(form:any){
    this.feddback_message_status=0;
    this.feedback_message="";

    if(form.value.room_capacity<1){
      this.feddback_message_status=2;
      this.feedback_message="invalid room capacity";
    }
  this.roomtypeservice.addroomType(form.value,{headers:this.authservice.getHeaders()}).subscribe(res=>{
    this.feddback_message_status=1;
    this.msg = res;
    this.feedback_message=this.msg.message;
    this.roomTypes=this.msg.roomtypes;

  },error=>{
    this.feddback_message_status=2;
    this.feedback_message=error.error.message;
  });

  }


}
