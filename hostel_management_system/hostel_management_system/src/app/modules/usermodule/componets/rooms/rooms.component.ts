import { RoomService } from './../../../../services/room.service';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subject } from 'rxjs';
import { Room } from 'src/app/models/room.model';
import { AuthService } from 'src/app/services/auth.service';
import Swal from 'sweetalert2';

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
  roomtypes={
box:'box',single:'single_room', double:'double_room'

  }

  rooms: any;
  room:any= new Room();
  constructor(private roomservice: RoomService,private authservice:AuthService) {}

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

    this.getRooms(1);

  }
  updateRoom() {
    this.roomservice.updateroom(this.room,{ headers: this.authservice.getHeaders() }).subscribe((res) => {
      console.log(res);
    });
  }

  deleteRoom() {
    console.log(this.room.room_id);
    this.roomservice
      .deleteRoom({ room_id: this.room.room_id },{ headers: this.authservice.getHeaders() })
      .subscribe((res) => {
        if (JSON.parse(JSON.stringify(res)).hasOwnProperty('message')) {
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            width: 200,
            title: 'Saved',
            customClass: 'swalheight',
            showConfirmButton: false,
            timer: 1500,
          });
        } else {
          Swal.fire({
            position: 'top-end',
            customClass: 'swalheight',

            title: 'Error!',
            text: 'thereb was an error',
            icon: 'error',
          });
        }
      });
  }

  setRoomId(room:any){
    this.room=room;
  }
  getRooms(hostel:any) {
    this.roomservice.getRooms(hostel,{ headers: this.authservice.getHeaders() }).subscribe((res) => {
      console.log(res);
      this.rooms = res;

      this.dtTrigger.next(null);
    });
  }
  ngOnDestroy(): void {
    this.dtTrigger.unsubscribe();
  }
}
