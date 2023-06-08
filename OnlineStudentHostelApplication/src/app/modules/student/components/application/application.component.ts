import { Route, Router } from '@angular/router';
import { Application } from './../../../../models/application';

import { Component, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { Student } from 'src/app/models/student';
import { ApplicationService } from 'src/app/services/application.service';
import { AuthService } from 'src/app/services/auth.service';
import { EncriprionserviceService } from 'src/app/services/encriprionservice.service';

@Component({
  selector: 'app-application',
  templateUrl: './application.component.html',
  styleUrls: ['./application.component.css'],
})
export class ApplicationComponent implements OnInit {
  feedback_message_status: any;
  feedback_message: any;
  student = new Student();
  rooms: any;
  msg: any;
  studentdatails: any;
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  application = new Application();
  batchdetails: any;
  constructor(
    private router: Router,
    private authservice: AuthService,
    private applicationservice: ApplicationService,
    private encservice: EncriprionserviceService
  ) {}
  ngOnInit(): void {
    this.feedback_message_status = 0;
    this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,
      pageLength: 5,
      lengthMenu: [5, 10, 20],
      lengthChange: true,
      language: {
        searchPlaceholder: 'search',
      },
    };
    this.studentdatails = localStorage.getItem('student');
    this.studentdatails = this.encservice.decrypt(this.studentdatails);
    this.batchdetails = localStorage.getItem('mybatch');
    this.batchdetails = this.encservice.decrypt(this.batchdetails);

    this.student.student_id = JSON.parse(this.studentdatails).student_id;
    this.student.reg_number = JSON.parse(this.studentdatails).reg_number;
    var sex = JSON.parse(this.studentdatails).sex;
    console.log(this.batchdetails);

    this.application.active_period_id = JSON.parse(
      this.batchdetails
    ).active_period_id;
    this.application.residence_session_id = JSON.parse(
      this.batchdetails
    ).residence_session_id;

    if (sex == 'MALE') {
      this.student.gender = 'm';
    } else if (sex == 'FEMALE') {
      this.student.gender = 'f';
    }

    console.log(sex);

    this.student.level = JSON.parse(this.studentdatails).academic_level;
    this.student.residence_session_id = JSON.parse(
      this.batchdetails
    ).residence_session_id;

    this.student.semester = JSON.parse(this.studentdatails).semester;
    this.student.period_id = JSON.parse(this.studentdatails).period_id;
    this.student.reg_number = JSON.parse(this.studentdatails).reg_number;
    this.student.active_period_id = JSON.parse(
      this.batchdetails
    ).active_period_id;

    this.getroomstoApply();
  }
  applyForARoom(room: any) {
    this.feedback_message_status = 0;

    this.application.applied_by = 'system';
    this.application.reg_number = this.student.reg_number;
    this.application.room_cost = room.room_price;
    this.application.room_id = room.room_id;
    this.application.student_id = this.student.student_id;
    this.application.reg_number = this.student.reg_number;
    console.log(this.application);

    this.applicationservice
      .applyForRoom(this.application, {
        headers: this.authservice.getHeaders(),
      })
      .subscribe(
        (res) => {
          console.log(res);

          this.msg = res;
          this.feedback_message_status = 1;
          this.feedback_message = this.msg.message;

          localStorage.setItem('hasapplied', this.encservice.encrypt('1'));
          setTimeout(() => {
            this.router.navigate(['student/home']);
          }, 3000);
        },
        (error) => {
          this.feedback_message_status = 2;
          this.feedback_message = error.error.message;
        }
      );
  }

  getroomstoApply() {
    console.log(this.student);

    this.applicationservice
      .getroomsToapply(this.student, { headers: this.authservice.getHeaders() })
      .subscribe(
        (res) => {
          console.log(res);
          this.rooms = res;
          this.dtTrigger.next(null);
        },
        (error) => {
          console.log(error);
        }
      );
  }
}
