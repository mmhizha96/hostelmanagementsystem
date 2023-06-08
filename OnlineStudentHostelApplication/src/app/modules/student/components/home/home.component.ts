import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Subject } from 'rxjs';
import { ApplicationService } from 'src/app/services/application.service';
import { AuthService } from 'src/app/services/auth.service';
import { EncriprionserviceService } from 'src/app/services/encriprionservice.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
})
export class HomeComponent implements OnInit {
  feedback_message_status: any;
  feedback_message: any;
  batches: any;
  level: any;
  myallocations: any;
  semester: any;
  period_id: any;
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  studentdatails: any;
  studentdatails1: any;

  constructor(
    private applicationservice: ApplicationService,
    private router: Router,
    private authservice: AuthService,
    private ennceservice: EncriprionserviceService
  ) {}
  ngOnInit(): void {
    this.studentdatails = localStorage.getItem('student');
    this.studentdatails1 = this.ennceservice.decrypt(this.studentdatails);
    // console.log(this.studentdatails);

    this.level = JSON.parse(this.studentdatails1).academic_level;

    this.semester = JSON.parse(this.studentdatails1).semester;
    this.period_id = JSON.parse(this.studentdatails1).period_id;
    this.getBaches();
    this.getMyAllocation();
  }

  getMyAllocation() {
    var regnumber = JSON.parse(this.studentdatails1).reg_number;
    this.applicationservice
      .getMyallocation(
        { regnumber: regnumber },
        {
          headers: this.authservice.getHeaders(),
        }
      )
      .subscribe((res) => {
        console.log(res);

        this.myallocations = res;
      });
  }
  gotoApplication(batch: any) {
    localStorage.setItem(
      'mybatch',
      this.ennceservice.encrypt(JSON.stringify(batch))
    );

    this.router.navigate(['student/application']);
  }
  getBaches() {
    var data = {
      period_id: this.period_id,
      level: this.level,
      semester: this.semester,
    };

    this.applicationservice
      .getMybatches(data, {
        headers: this.authservice.getHeaders(),
      })
      .subscribe(
        (res) => {
          this.batches = res;
        },
        (error) => {
          console.log(error);
        }
      );
  }
}
