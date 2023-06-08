import { Component, OnInit } from '@angular/core';
import { Activeperiods } from 'src/app/models/activeperiods.model';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { AuthService } from 'src/app/services/auth.service';
import { LevelsService } from 'src/app/services/levels.service';
import { PeriodsService } from 'src/app/services/periods.service';
import { YearserviceService } from 'src/app/services/yearservice.service';

@Component({
  selector: 'app-createactiveperiod',
  templateUrl: './createactiveperiod.component.html',
  styleUrls: ['./createactiveperiod.component.css']
})
export class CreateactiveperiodComponent implements OnInit {

  activeperiod= new Activeperiods();
  periods:any;
  levels:any;
  years:any;
  feedback_status: any;
  feedbackmsg: any;
  result: any
  batches: any;


  constructor(private activeperiodservice:ActiveperiodsService,private authservice:AuthService,private periodservice:PeriodsService,private yearservice:YearserviceService, private levelservice:LevelsService){

  }
  initfields(){
    this.activeperiod.level="";
    this.activeperiod.semester="";
    this.activeperiod.period_id="";
  }
  ngOnInit(): void {
   this.initfields();
    this.getlevel();
    this.getperiods()
   this.getYears();
  }
  getlevel(){

    this.levelservice.getlevels({ headers: this.authservice.getHeaders() }).subscribe(res=>{

      this.levels=res;

    })
  }
  getYears(){
this.yearservice.getyears({ headers: this.authservice.getHeaders() }).subscribe(res=>{


this.years=res;
})
  }
  getperiods(){


    this.periodservice.getperiods({ headers: this.authservice.getHeaders() }).subscribe(res=>{
console.log(res);

      this.periods=res;
    })
  }
  createActivePeriod(){
    this.activeperiod.is_active='1';
    this.activeperiod.activated_by='1';
    this.feedback_status = "";


    this.feedbackmsg = "";

    console.log(JSON.parse(JSON.stringify(this.activeperiod)));
    this.activeperiodservice.createActiveperiod(this.activeperiod,{ headers: this.authservice.getHeaders() }).subscribe(
      (res) => {
        this.feedback_status = 1;
        this.result = res;

        this.feedbackmsg = this.result.message;
      },
      (error) => {
        this.feedback_status = 2;
        this.feedbackmsg = error.error.message;
      }
    );

  }

}
