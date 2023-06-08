import { Component, OnInit } from '@angular/core';
import { error } from 'jquery';
import { Subject } from 'rxjs';
import { UserRights } from 'src/app/models/user-rights.model';
import { AuthService } from 'src/app/services/auth.service';
import { ParamsService } from 'src/app/services/params.service';

@Component({
  selector: 'app-user-rights',
  templateUrl: './user-rights.component.html',
  styleUrls: ['./user-rights.component.css'],
})
export class UserRightsComponent implements OnInit {
  user: any;
  myrights: any;
  rightstoadd: any;
  response: any;
  feedback_message: any;
  right=new UserRights();
  feedback_message_status: any;
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  constructor(
    private paramservice: ParamsService,
    private authservice: AuthService
  ) {}
  ngOnInit(): void {
    this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,

      lengthChange: false,
      language: {
        searchPlaceholder: 'search',
      },
      destroy: true,
    };
    this.user = this.paramservice.getparam('userright');

    this.getrights();
  }

  AssignRight() {

var rights= JSON.stringify(this.right);
    this.feedback_message_status = 0;
    this.feedback_message = '';
    var assigned_by=this.authservice.getUserId();
    this.authservice
      .assignRights(
        { user_id: this.user.user_id, rights: rights,assigned_by:assigned_by },
        { headers: this.authservice.getHeaders() }
      )
      .subscribe(
        (res) => {
          console.log(res);

          this.response = res;
          this.myrights = this.response.myrights;
          this.rightstoadd = this.response.rightstoadd;
          this.feedback_message_status = 1;
          this.feedback_message = this.response.message;
          this.dtTrigger.next;
        },
        (error) => {
          this.feedback_message_status = 2;
          this.feedback_message = error.error.message;
        }
      );
  }


  getrights() {
    this.authservice
      .getrights(
        { user_id: this.user.user_id },
        { headers: this.authservice.getHeaders() }
      )
      .subscribe(
        (res) => {
          console.log(res);

          this.response = res;

      this.right= JSON.parse(this.response.rights);

          this.dtTrigger.next(null);
        },
        (error) => {
          console.log(error);
        }
      );
  }
}
