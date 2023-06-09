import { Activeperiods } from './../../../../models/activeperiods.model';

import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Subject } from 'rxjs';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { YearserviceService } from 'src/app/services/yearservice.service';
import { LevelsService } from 'src/app/services/levels.service';
import { AuthService } from 'src/app/services/auth.service';
import { PeriodsService } from 'src/app/services/periods.service';
import { ParamsService } from 'src/app/services/params.service';
import { error } from 'jquery';
import { UserRights } from 'src/app/models/user-rights.model';

@Component({
  selector: 'app-periods',
  templateUrl: './periods.component.html',
  styleUrls: ['./periods.component.css'],
})
export class PeriodsComponent implements OnInit {
  activeperiod = new Activeperiods();
  periods: any;
  active_periods: any;
  levels: any;
  years: any;
  feedback_status: any;
  feedbackmsg: any;
  result: any;
  batches: any;
  userrole: any;
  myrights = new UserRights();
  date = new Date();
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();

  activeperiods: any = new Activeperiods();

  constructor(
    private activeperiodservice: ActiveperiodsService,
    private authservice: AuthService,
    private router: Router,
    private periodservice: PeriodsService,
    private yearservice: YearserviceService,
    private levelservice: LevelsService,
    private paramsservice: ParamsService
  ) {}
  gotoBatches(activeperiod: any) {
    this.paramsservice.setparam('activeperiod', activeperiod);
    this.router.navigate(['/admin/batches']);
  }
  initfields() {
    this.activeperiod.level = '';
    this.activeperiod.semester = '';
    this.activeperiod.period_id = '';
  }
  ngOnInit(): void {
    this.myrights = this.paramsservice.getparam('myrights');
    this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,

      lengthChange: false,
      language: {
        searchPlaceholder: 'search',
      },
    };
    this.userrole = this.authservice.getRole();
    this.initfields();
    this.getActivePeriods();
    this.getperiods();
    this.getlevels();
    this.getyears();
  }
  deactivate() {
    this.feedback_status = 0;
    this.activeperiodservice
      .deactivateActiveperiod(
        {
          active_period_id: this.activeperiods.active_period_id,
        },
        { headers: this.authservice.getHeaders() }
      )
      .subscribe(
        (res) => {
          this.feedback_status = 1;
          this.feedbackmsg = 'period successfully deactivated ';
          this.result = res;
          this.active_periods = this.result.periods;
        },
        (error) => {
          this.feedback_status = 2;
          this.feedbackmsg = error.error.message;
        }
      );
  }
  getperiods() {
    this.periodservice
      .getperiods({ headers: this.authservice.getHeaders() })
      .subscribe((res) => {
        this.periods = res;
      },error=>{
        console.log(error);

      });
  }
  createActivePeriod() {
    this.activeperiod.is_active = '1';
    this.activeperiod.activated_by = '1';
    this.feedback_status = '';

    this.feedbackmsg = '';
    if (this.activeperiod.end_date <= this.activeperiod.start_date) {
      this.feedback_status = 2;
      this.feedbackmsg = 'end date must be greater start date ';
    } else {
      this.activeperiodservice
        .createActiveperiod(this.activeperiod, {
          headers: this.authservice.getHeaders(),
        })
        .subscribe(
          (res) => {
            this.feedback_status = 1;
            this.result = res;

            this.feedbackmsg = this.result.message;
            this.active_periods = this.result.periods;
          },
          (error) => {
            this.feedback_status = 2;
            this.feedbackmsg = error.error.message;
          }
        );
    }
  }
  activate() {
    this.feedback_status = 0;
    this.activeperiodservice
      .activateActiveperiod(
        {
          active_period_id: this.activeperiods.active_period_id,
        },
        { headers: this.authservice.getHeaders() }
      )
      .subscribe(
        (res) => {
          this.feedback_status = 1;
          this.feedbackmsg = 'period successfully activated ';
          this.result = res;
          this.active_periods = this.result.periods;
        },
        (error) => {
          this.feedback_status = 2;
          this.feedbackmsg = error.error.message;
        }
      );
  }
  delete() {
    this.feedback_status = 0;
    this.activeperiodservice
      .deleteActiveperiod(
        {
          active_period_id: this.activeperiods.active_period_id,
        },
        { headers: this.authservice.getHeaders() }
      )
      .subscribe(
        (res) => {
          this.feedback_status = 1;
          this.feedbackmsg = 'period successfully deleted ';
          this.result = res;
          this.active_periods = this.result.periods;
        },
        (error) => {
          this.feedback_status = 2;
          this.feedbackmsg = error.error.message;
        }
      );
  }

  updateactiveperiod() {
    this.feedback_status = 0;
    this.activeperiodservice
      .updateActtivePeriod(this.activeperiods, {
        headers: this.authservice.getHeaders(),
      })
      .subscribe(
        (res) => {
          this.feedback_status = 1;

          this.result = res;
          this.feedbackmsg = this.result.message;
          this.active_periods = this.result.periods;
        },
        (error) => {
          this.feedback_status = 2;
          this.feedbackmsg = error.error.message;
        }
      );
  }

  setcreds(activeperiod: any) {
    this.activeperiods = activeperiod;
    this.activeperiods.level = parseInt(activeperiod.level);
    this.activeperiods.semester = parseInt(activeperiod.semester);
  }
  getyears() {
    this.yearservice
      .getyears({ headers: this.authservice.getHeaders() })
      .subscribe((res) => {
        this.years = res;
      });
  }
  getlevels() {
    this.levelservice
      .getlevels({ headers: this.authservice.getHeaders() })
      .subscribe((res) => {
        this.levels = res;
      });
  }
  getActivePeriods() {
    this.activeperiodservice
      .getActivePeriod({ headers: this.authservice.getHeaders() })
      .subscribe(
        (res) => {
          this.active_periods = res;

          console.log(res);

          this.dtTrigger.next(null);
        },
        (error) => {
          console.log(error);
        }
      );
  }
}
