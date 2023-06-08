import { Activeperiods } from 'src/app/models/activeperiods.model';
import { AuthService } from 'src/app/services/auth.service';
import { Component, OnInit } from '@angular/core';
import { Subject, isEmpty } from 'rxjs';
import { RoomService } from 'src/app/services/room.service';
import { Resavation } from 'src/app/models/resavation';
import { ParamsService } from 'src/app/services/params.service';
import { BatchesService } from 'src/app/services/batches.service';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { Batch } from 'src/app/models/batch.model';
import { UserRights } from 'src/app/models/user-rights.model';

@Component({
  selector: 'app-resavations',
  templateUrl: './resavations.component.html',
  styleUrls: ['./resavations.component.css']
})
export class ResavationsComponent implements OnInit {

  dtoptions: DataTables.Settings = {};
rooms:any;
batches:any;
session_batch:any;
roomstoreserve:any;
active_Periods:any;
resevation=new Resavation();
  dtTrigger: Subject<any> = new Subject<any>();
 feddback_message_status: any;
  msg: any;
  myrights=new UserRights();
  batchmodel= new Batch();
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
    this.getAllBatch();
    this.getResevations();
    this.activePedriods();
    this.session_batch=this.params.getparam("batch");
   if(this.session_batch==null){
    this.batchmodel.active_period_id="";
    this.batchmodel.residence_session_id="";
   }

else{
  this.batchmodel.active_period_id=this.session_batch.active_period_id;
    this.batchmodel.residence_session_id=this.session_batch.residence_session_id;
   this.getroomsToresereve();
}


  }
  constructor(private authservice:AuthService,private roomservice:RoomService,private params:ParamsService,private batchservice:BatchesService,private activeperiodservice:ActiveperiodsService){

  }
getroomsToresereve(){


this.roomservice.getRoomstoreserve({residence_session_id:this.batchmodel.residence_session_id},{headers:this.authservice.getHeaders()}).subscribe(res=>{
 this.roomstoreserve=res;
console.log(res);

},error=>{
  console.log(error);

})
}

activePedriods(){
  this.activeperiodservice.getActivePeriod({headers:this.authservice.getHeaders()}).subscribe(res=>{
   this.active_Periods=res;
   console.log(res);


  },error=>{
    console.log(error);

  })
}
getBatches(active_period_id:any){
this.batchmodel.residence_session_id='';

  this.batchservice.getBatches({active_period_id:active_period_id},{headers:this.authservice.getHeaders()}).subscribe(res=>{
    this.batches=res;

  },error=>{
    console.log(error);

  })
}
getAllBatch(){
  this.batchservice.getBatchesAll({headers:this.authservice.getHeaders()}).subscribe(res=>{
    this.batches=res;

  },error=>{
    console.log(error);

  })
}

reserve(room:any){



  this.resevation=room;
  this.resevation.residence_session_id=this.batchmodel.residence_session_id;

  this.resevation.reserved_by=this.authservice.getUserId();

  this.roomservice.reserveRoom(this.resevation,{ headers: this.authservice.getHeaders() }).subscribe(res=>{
   this.feddback_message_status=1;
   this.msg = res;
   this.feedback_message=this.msg.message;
   this.getroomsToresereve();
this.getResevations();

 },error=>{
   this.feddback_message_status=2;
   this.feedback_message=error.error.message;
 });
 }

batchChange(){
this.findResavation(this.batchmodel.residence_session_id);
this.getroomsToresereve();
}

  unReserve(){
this.roomservice.unReserveRoom(this.resevation,{headers:this.authservice.getHeaders()}).subscribe(
  (res) => {
    this.feddback_message_status = 1;
    this.msg = res;
    this.feedback_message = this.msg.message;
this.getroomsToresereve();
this.getResevations();

  },
  (error) => {
    console.log(error);

    this.feddback_message_status = 2;
    this.feedback_message = error.error.message;
  }
);
  }
  setRoom(room:any){
this.resevation=room;
  }
findResavation(residence_session_id:any){
  this.roomservice.findReservedRoom({residence_session_id:this.batchmodel.residence_session_id},{headers:this.authservice.getHeaders()}).subscribe(
    (res) => {
this.rooms=res;
    },
    (error) => {
      console.log(error);

      this.feddback_message_status = 2;
      this.feedback_message = error.error.message;
    }
  );
}

  getResevations(){
    this.roomservice.getReservedRoom({headers:this.authservice.getHeaders()}).subscribe(
      (res) => {
this.rooms=res;

console.log(res);



      },
      (error) => {
        console.log(error);

        this.feddback_message_status = 2;
        this.feedback_message = error.error.message;
      }
    );
  }
  }



