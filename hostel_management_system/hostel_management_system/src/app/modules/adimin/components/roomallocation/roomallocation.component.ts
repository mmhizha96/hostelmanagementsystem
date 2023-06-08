import { BatchesService } from './../../../../services/batches.service';
import { Batch } from './../../../../models/batch.model';
import { Component } from '@angular/core';
import { NgForm } from '@angular/forms';
import { Subject } from 'rxjs';
import { Roomallocation } from 'src/app/models/roomallocation';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { AuthService } from 'src/app/services/auth.service';

import { ParamsService } from 'src/app/services/params.service';
import { RoomallocationService } from 'src/app/services/roomallocation.service';
import { error } from 'jquery';
import { UserRights } from 'src/app/models/user-rights.model';

@Component({
  selector: 'app-roomallocation',
  templateUrl: './roomallocation.component.html',
  styleUrls: ['./roomallocation.component.css'],
})
export class RoomallocationComponent {
  batch: any;
  activeperiodid: any;

  batchallocations_appproved: any;
  batchallocations_pending: any;
  feddback_message_status: any;
  msg: any;
  $reg_number: any;
  roomgender: any;
  room_type_id: any;
  roomstoallocate: any;
  residence_session_id: any;
  feedback_message: any;
  myrights=new UserRights();
  periods: any;
  userrole: any;
  residenceSession: any;
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  roomallocation = new Roomallocation();
  active_period_id: any;
  ngOnInit(): void {
    this.dtoptions = {
      searching: true,

      pageLength: 5,

      lengthChange: true,

      language: {
        searchPlaceholder: 'search',
      },
      destroy: true,
    };


    this.myrights=this.params.getparam('myrights');
    this.userrole = this.authservice.getRole();

    this.roomgender = 'm';
    this.residenceSession = this.params.getparam('batch');
    this.residence_session_id = this.residenceSession.residence_session_id;
    this.active_period_id = this.residenceSession.active_period_id;
    this.getroomsToallocate();
    this.getRoomsallocations();
  }
  constructor(
    private authservice: AuthService,
    private roomallocationservice: RoomallocationService,
    private params: ParamsService,
    private activeperiodservice: ActiveperiodsService,
    private batchesService: BatchesService
  ) {}

  getRoomsallocations() {
    this.roomallocationservice
      .getRoomallocation(
        { residence_session_id: this.residence_session_id },
        { headers: this.authservice.getHeaders() }
      )
      .subscribe(
        (res) => {
          this.msg = res;
          // console.log(res);

          this.batchallocations_appproved = this.msg.allocations_appproved;
          this.batchallocations_pending = this.msg.allocations_pending;
          this.dtTrigger.next(null);
        },
        (error) => {
          console.log(error);
        }
      );
  }

  getroomsToallocate() {
    if (this.roomgender != null) {
      var data = {
        room_gender: this.roomgender,
        batch_id: this.residence_session_id,
        active_period_id: this.active_period_id,
      };
      this.roomallocationservice
        .getroomstoallocate(data, { headers: this.authservice.getHeaders() })
        .subscribe(
          (res) => {
            this.roomstoallocate = res;
            // console.log(res);
          },
          (error) => {
            console.log(error);
          }
        );
    }
    this.roomstoallocate = null;
  }
  setroomid(room_id: any) {
    this.roomallocation.room_id = room_id;
  }

  approve_reject(status: any, room_allocation_id: any) {
    var data = {
      approved_status: status,
      residence_session_id: this.residenceSession.residence_session_id,
      room_allocation_id: room_allocation_id,
    };
    this.roomallocationservice
      .approve_reject(data, {
        headers: this.authservice.getHeaders(),
      })
      .subscribe(
        (res) => {
this.msg=res;

          this.batchallocations_appproved = this.msg.allocations_appproved;

          console.log(this.msg.allocations_appproved);

          this.batchallocations_pending = this.msg.allocations_pending;
          this.dtTrigger.next;
        },
        (error) => {
          console.log(error);
        }
      );
  }

  allocateRooms() {
    this.feddback_message_status = 0;
    this.feedback_message = '';
    this.roomallocation.active_period_id =
      this.residenceSession.active_period_id;
    this.roomallocation.allocated_by = this.authservice.getUserId();
    this.roomallocation.residence_session_id =
      this.residenceSession.residence_session_id;

    this.roomallocationservice
      .allocateRooms(this.roomallocation, {
        headers: this.authservice.getHeaders(),
      })
      .subscribe(
        (res) => {
          this.feddback_message_status = 1;
          this.msg = res;
          this.feedback_message = this.msg.message;
          this.batchallocations_pending = this.msg.rooms;
          // console.log(res);
        },
        (error) => {
          console.log(error);

          this.feddback_message_status = 2;
          this.feedback_message = error.error.message;
        }
      );
  }
}
