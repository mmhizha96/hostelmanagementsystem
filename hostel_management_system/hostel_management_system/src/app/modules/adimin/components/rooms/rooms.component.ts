import { RoomtypeService } from './../../../../services/roomtype.service';
import { ParamsService } from './../../../../services/params.service';
import { RoomService } from './../../../../services/room.service';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subject } from 'rxjs';
import { Room } from 'src/app/models/room.model';
import Swal from 'sweetalert2';
import { AuthService } from 'src/app/services/auth.service';
import { Resavation } from 'src/app/models/resavation';
import { error } from 'jquery';
import { FloorService } from 'src/app/services/floor.service';
import { HostelService } from 'src/app/services/hostel.service';
import { UserRights } from 'src/app/models/user-rights.model';

@Component({
  selector: 'app-rooms',
  templateUrl: './rooms.component.html',
  styleUrls: ['./rooms.component.css'],
})
export class RoomsComponent implements OnInit, OnDestroy {
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  roomgender={
    male:'m',
    female:'f'
  }
  roomtypes:any;
hostelid:any;
myrights= new UserRights();
  rooms: any;
  room:any= new Room();
  feddback_message_status: any;
  msg: any;
  resavation=new Resavation();
  feedback_message: any;
  hostels:any;
  floors:any;
  userrole:any;
  constructor(private roomservice: RoomService,private floorservice: FloorService,private roomtypeservice:RoomtypeService, private hostelservice:HostelService, private paramsService:ParamsService,private roomtypeService:RoomtypeService,private authservice:AuthService) {}

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

  ;
  this.userrole=this.authservice.getRole();

  this.myrights=this.paramsService.getparam('myrights');
  this.room.room_gender="";
  this.room.floor_id="";
  this.room.room_type_id="";
  this.hostelid=this.gethostel();
  this.room.hostel_id=this.hostelid;
    this.getRooms(this.hostelid);
    this.getRoomType();
    this.getHostel();
    this.getFloors(this.hostelid);
  }

  // constructor(private authservice:AuthService, private roomservice:RoomService,  private hostelservice:HostelService,private ){}
createRooms(){
  this.feddback_message_status=0;
    this.feedback_message="";
  this.roomservice.createRooms(this.room,{ headers: this.authservice.getHeaders() }).subscribe(res=>{
    this.feddback_message_status=1;
    this.msg = res;
    this.feedback_message=this.msg.message;
    this.rooms=this.msg.rooms


  },error=>{
    this.feddback_message_status=2;
    this.feedback_message=error.error.message;
  }
    )

}
getFloors(hostel_id:any){


this.floorservice.getFloor({ hostel_id: hostel_id },{headers:this.authservice.getHeaders()}).subscribe(res=>{
  this.floors=res;


}
)


this.roomservice.getRooms({ hostel_id: hostel_id },{headers:this.authservice.getHeaders()}).subscribe(res=>{
this.rooms=res;


})
}

getHostel(){
this.hostelservice.getHostels({headers:this.authservice.getHeaders()}).subscribe(res=>{
 this.hostels=res;
})
}
getRoomType(){
this.roomtypeservice.getRoomType({headers:this.authservice.getHeaders()}).subscribe(res=>{
  console.log(res);
this.roomtypes=res;
}
,error=>{
  console.log(error);

})
}
  getroomtpes(){
    this.roomtypeService.getRoomType({headers:this.authservice.getHeaders()}).subscribe(res=>{
      this.roomtypes=res;

    })
  }
  updateRoom() {
    this.roomservice.updateroom(this.room,{ headers: this.authservice.getHeaders() }).subscribe(res=>{
      this.feddback_message_status=1;
      this.msg = res;
      this.feedback_message=this.msg.message;
      this.rooms=this.msg.rooms;

    },error=>{
      this.feddback_message_status=2;
      this.feedback_message=error.error.message;
    });

  }


  reserve(){

   this.resavation=this.room;
   this.resavation.reserved_by=this.authservice.getUserId();
   console.log(this.resavation);
   this.roomservice.reserveRoom(this.resavation,{ headers: this.authservice.getHeaders() }).subscribe(res=>{
    this.feddback_message_status=1;
    this.msg = res;
    this.feedback_message=this.msg.message;

  },error=>{
    this.feddback_message_status=2;
    this.feedback_message=error.error.message;
  });
  }
  deleteRoom() {
    this.feddback_message_status=0;
    this.feedback_message="";
    this.roomservice
      .deleteRoom({ room_id: this.room.room_id },{ headers: this.authservice.getHeaders() })
      .subscribe(res=>{
        this.feddback_message_status=1;
        this.msg = res;
        this.feedback_message=this.msg.message;

      },error=>{
        this.feddback_message_status=2;
        this.feedback_message=error.error.message;
      });
  }

  setRoomId(room:any){
    this.room=room;
    this.getroomtpes();
  }
  gethostel()
  {
const hostel=this.paramsService.getparam('hostel');
// console.log( );
return hostel.hostel_id;

  }
  getRooms( hostel_id:any ) {
    this.roomservice.getRooms({hostel_id:hostel_id},{ headers: this.authservice.getHeaders() }).subscribe((res) => {

      this.rooms = res;

      this.dtTrigger.next(null);
    });
  }
  ngOnDestroy(): void {
    this.dtTrigger.unsubscribe();
  }
}
