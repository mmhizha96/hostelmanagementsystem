import { Component, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { BatchesService } from 'src/app/services/batches.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Batch } from 'src/app/models/batch.model';
import { LevelsService } from 'src/app/services/levels.service';
import { YearserviceService } from 'src/app/services/yearservice.service';
import { AuthService } from 'src/app/services/auth.service';
import { ParamsService } from 'src/app/services/params.service';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { UserRights } from 'src/app/models/user-rights.model';

@Component({
  selector: 'app-batches',
  templateUrl: './batches.component.html',
  styleUrls: ['./batches.component.css'],
})
export class BatchesComponent implements OnInit {
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  urlparams: any;
  batches: any;
  result: any;
  error_result: any;
  levels: any;
  activeperiods:any;
  years: any;
  batchmodel = new Batch();
  feedback_status: any;
  feedbackmsg: any;
  batch= new Batch();
  date = new Date();
  msg: any;
  myrights=new UserRights();
  min_date:any;
  max_date:any;
  errormsg: any;
  current_activeperiod_id:any;
  userrole:any;

  ngOnInit(): void {

    this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,

      lengthChange: false,
      language: {
        searchPlaceholder: 'search',
      },
destroy:true,
    };
    this.myrights = this.paramsservice.getparam('myrights');
    this.userrole=this.authservice.getRole();
    this.current_activeperiod_id=this.paramsservice.getparam('activeperiod').active_period_id;
    this.batch.active_period_id=this.current_activeperiod_id;
    this.min_date=this.paramsservice.getparam('activeperiod').start_date;
    this.max_date=this.paramsservice.getparam('activeperiod').end_date;
    this.batch.level="";
    this.batch.semester="";
    this.getActivePeriods();
    this.getBatches();
    this.getYears();
    this.getlevel();
  }
  constructor(
    private batchservice: BatchesService,

    private levelservice: LevelsService,
    private yearservice: YearserviceService,
    private authservice:AuthService,
    private activeperiodservice:ActiveperiodsService,
    private paramsservice:ParamsService,
    private router:Router
  ) {}
  createBatch(){
    this.feedback_status=0;
    this.batch.is_program_driven="0";
    this.batch.available_status="0";
if(this.batch.end_date<=this.batch.start_date){
  this.feedback_status=2;
      this.feedbackmsg="end date must be greater start date "
}else{

    this.batchservice.createBatch(this.batch,{ headers: this.authservice.getHeaders() }).subscribe(res=>{

        this.feedback_status=1;
        this.msg = res;
        this.feedbackmsg=this.msg.message;


        this.batches = this.msg.residence_session;



    },(error)=>{

      if(error.status==500)
    {
      this.feedback_status=2;
      this.feedbackmsg=error.error.message;
    }


    this.feedback_status=2;
    this.feedbackmsg=error.error.message;




    if(error.status==403)
    {
      this.feedback_status=2;
      this.feedbackmsg=error.error.message;
    }

    })
      }}
  setcreds(batch: any) {
    this.batchmodel = batch;
  }
  getActivePeriods(){
    this.activeperiodservice.getCurrentActivePeriod({ headers: this.authservice.getHeaders() }).subscribe(res=>{
      this.activeperiods=res;
      console.log(res);



    })

      }
  getBatches() {


    this.batchservice
      .getBatches({ active_period_id: this.current_activeperiod_id },{ headers: this.authservice.getHeaders() })
      .subscribe(
        (res) => {
          this.result = res;
          this.batches = this.result;
          this.dtTrigger.next(null);
        },
        (error) => {
          this.error_result = 'An error occured';
        }
      );
  }
  intifields(){
    this.batch.active_period_id="";
    this.batch.level="";
    this.batch.semester="";
  }
  getlevel() {
    this.levelservice.getlevels({ headers: this.authservice.getHeaders() }).subscribe((res) => {
      this.levels = res;
      console.log(this.levels);
    });
  }
  goTO(path:any,batch:any){
this.paramsservice.setparam("batch",batch);
this.router.navigate([path]);

  }
  getYears() {
    this.yearservice.getyears({ headers: this.authservice.getHeaders() }).subscribe((res) => {
      this.years = res;
      console.log(this.years);
    });
  }
  delete() {
    this.feedbackmsg = null;
    this.feedback_status = 0;
    this.batchservice.deletebatch(this.batchmodel,{ headers: this.authservice.getHeaders() }).subscribe(
      (res) => {
        this.feedback_status = 1;
        this.feedbackmsg = 'batch successfully deleted ';
        this.result = res;
        this.batches = this.result.residence_session;
      },
      (error) => {
        this.feedback_status = 2;
        this.feedbackmsg = error.error.message;
      }
    );
  }
  deactivate() {
    this.feedbackmsg = null;
    this.feedback_status = 0;
    this.feedback_status = 0;
    this.batchservice.deactivate(this.batchmodel,{ headers: this.authservice.getHeaders() }).subscribe(
      (res) => {
        this.feedback_status = 1;
        this.result = res;
        this.batches = this.result.residence_session;
        this.feedbackmsg = this.result.message;
      },
      (error) => {
        this.feedback_status = 2;
        this.feedbackmsg = error.error.message;
      }
    );
  }
  activate() {
    this.feedbackmsg = null;
    this.feedback_status = 0;
    this.batchservice.activateBatch(this.batchmodel,{ headers: this.authservice.getHeaders() }).subscribe(
      (res) => {
        this.feedback_status = 1;

        this.result = res;
        this.batches = this.result.residence_session;
        this.feedbackmsg = this.result.message;
      },
      (error) => {
        this.feedback_status = 2;
        this.feedbackmsg = error.error.message;
      }
    );
  }
sethostelPreference(batch:any){
// localStorage.setItem("batch")
}


  UpdateBatch() {
    this.feedbackmsg = null;
    this.feedback_status = 0;
    this.batchservice.updateBatch(this.batchmodel,{ headers: this.authservice.getHeaders() }).subscribe(
      (res) => {
        this.feedback_status = 1;

        this.result = res;
        this.batches = this.result.residence_session;
        this.feedbackmsg = this.result.message;
      },
      (error) => {
        this.feedback_status = 2;

        this.feedbackmsg = error.error.message;
      }
    );
  }
}
