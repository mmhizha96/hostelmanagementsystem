import { Component, OnInit } from '@angular/core';
import { Subject, count } from 'rxjs';
import { Hostelpreference } from 'src/app/models/hostelpreference';
import { AuthService } from 'src/app/services/auth.service';
import { HostelpreferenceService } from 'src/app/services/hostelpreference.service';
import { ParamsService } from 'src/app/services/params.service';
import { RoompreferenceService } from 'src/app/services/roompreference.service';

@Component({
  selector: 'app-hostelpreference',
  templateUrl: './hostelpreference.component.html',
  styleUrls: ['./hostelpreference.component.css']
})
export class HostelpreferenceComponent implements OnInit {
  hostelstoadd:any;
  batchhostels:any;
  residenceSession:any;
  feddback_message_status: any;
  count:any;
msg: any;
hostelpreference= new Hostelpreference();
feedback_message: any;
userrole:any;

  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  ngOnInit(): void {
    this.userrole=this.authservice.getRole();
    this.count=0;
    this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,
pageLength:7,
      lengthChange: true,
      language: {
        searchPlaceholder: 'Text Customer',
      },
    };
    this.residenceSession=this.params.getparam("batch");
console.log( this.residenceSession.residence_session_id);

this.gethostelstoAdd();
this.getBatchHostels();
  }
  constructor(private authservice:AuthService,
    private hostelpreferenceservice:HostelpreferenceService,
    private params:ParamsService
    ){

  }


setHostelPreference(hostel:any){
  this.feddback_message_status = 0;
  this.feedback_message = "";
  this.hostelpreference=hostel;
  this.hostelpreference.residence_session_id=this.residenceSession.residence_session_id;
  this.hostelpreference.activated_by=this.authservice.getUserId();
  this.hostelpreference.active_period_id=this.residenceSession.active_period_id;
  console.log(this.hostelpreference);


this.hostelpreferenceservice.sethostelpreference(this.hostelpreference,{ headers: this.authservice.getHeaders() }).subscribe(
  (res) => {
    this.feddback_message_status = 1;
    this.msg = res;
    this.feedback_message = this.msg.message;
    this.batchhostels=this.msg.hostelpreference;
    this.hostelstoadd=this.msg.hostelstoadd;
  },
  (error) => {
    console.log(error);

    this.feddback_message_status = 2;
    this.feedback_message = error.error.message;
  }
);

}
removeHostel(hostel:any)
{
  this.feddback_message_status = 0;
  this.feedback_message = "";

this.hostelpreferenceservice.deletehostelpreference(hostel,{ headers: this.authservice.getHeaders() }).subscribe(
  (res) => {
    this.feddback_message_status = 1;
    this.msg = res;
    this.feedback_message = this.msg.message;
this.batchhostels=this.msg.hostelpreference;
this.hostelstoadd=this.msg.hostelstoadd;

  },
  (error) => {
    console.log(error);

    this.feddback_message_status = 2;
    this.feedback_message = error.error.message;
  }
);
}

gethostelstoAdd(){
  this.hostelpreferenceservice.getHostelsToAdd({residence_session_id:this.residenceSession.residence_session_id},{ headers: this.authservice.getHeaders() }).subscribe(
    res=>{
this.hostelstoadd=res;

if(this.count==0)
  this.dtTrigger.next;

    },error=>{
   console.log("server error");


    }
  )
}
getBatchHostels(){

  this.hostelpreferenceservice.gethostelpreference({residence_session_id:this.residenceSession.residence_session_id},{headers:this.authservice.getHeaders()}).subscribe(
    res=>{
this.batchhostels=res;
console.log(res);





    },error=>{
      console.log(error);

    }
  )
}



}
