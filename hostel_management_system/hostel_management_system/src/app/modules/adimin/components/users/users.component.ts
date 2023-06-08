import { error } from 'jquery';
import { AuthService } from 'src/app/services/auth.service';
import { Component, OnInit } from '@angular/core';
import { NgForm } from '@angular/forms';
import { User } from 'src/app/models/user';
import { Subject } from 'rxjs';
import { ParamsService } from 'src/app/services/params.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.css'],
})
export class UsersComponent implements OnInit {
  msg: any;
  dtoptions: DataTables.Settings = {};
  dtTrigger: Subject<any> = new Subject<any>();
  feedback_message: any;
  feedback_message_status: any;
  users: any;
  usermodel = new User();
  ngOnInit(): void {
    this.dtoptions = {
      pagingType: 'full_numbers',
      searching: true,

      lengthChange: false,
      language: {
        searchPlaceholder: 'search',
      },
    };
    this.usermodel.role = '';
    this.usermodel.is_active = '1';
    this.getUsers();
  }

  constructor(private authService: AuthService,
   private paramsservice:ParamsService,
   private router:Router) {}


activateUser(user_id:any){
  this.feedback_message_status = 0;
  this.feedback_message = '';
  this.authService.activateUser({user_id:user_id},{ headers: this.authService.getHeaders() }).subscribe(
    (res) => {
      this.feedback_message_status = 1;
      this.msg = res;
      this.feedback_message = this.msg.message;
      this.users = this.msg.users;
    },
    (error) => {
      this.feedback_message_status = 2;
      this.feedback_message = error.error.message;
    }
  );
}
DeactivateUser(user_id:any){
  this.feedback_message_status = 0;
  this.feedback_message = '';
  this.authService.deactivateUser({user_id:user_id},{ headers: this.authService.getHeaders() }).subscribe(
    (res) => {
      this.feedback_message_status = 1;
      this.msg = res;
      this.feedback_message = this.msg.message;
      this.users = this.msg.users;
    },
    (error) => {
      this.feedback_message_status = 2;
      this.feedback_message = error.error.message;
    }
  );
}

gotorights(user:any){
this.paramsservice.setparam('userright',user);
this.router.navigate(['admin/rights']);
}


  getUsers() {
    this.feedback_message_status = 0;
    this.feedback_message = '';
    this.authService
      .getUsers({ headers: this.authService.getHeaders() })
      .subscribe(
        (res) => {
          this.users = res;
        },
        (error) => {
          this.feedback_message_status = 2;
          this.feedback_message = 'server error';
        }
      );
  }

  createuser(user: any) {
    this.feedback_message_status = 0;
    this.feedback_message = '';
    user.value.password=user.value.email;
    console.log(user.value);

    this.authService
      .register(user.value, { headers: this.authService.getHeaders() })
      .subscribe(
        (res) => {
          this.feedback_message_status = 1;
          this.msg = res;
          this.feedback_message = this.msg.message;
          this.users = this.msg.users;
        },
        (error) => {
          this.feedback_message_status = 2;
          this.feedback_message = error.error.message;
        }
      );
  }
}
