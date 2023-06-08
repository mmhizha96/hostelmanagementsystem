import { EncriprionserviceService } from './../../services/encriprionservice.service';
import { NgForm } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Student } from 'src/app/models/student';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent implements OnInit {
  loading: any;
  error: any;
  studentdetails: any;
  student = new Student();
  constructor(
    private router: Router,
    private auhthservice: AuthService,
    private encservice: EncriprionserviceService
  ) {}

  ngOnInit(): void {
    this.loading = false;
    this.error = null;
  }
  hideError() {
    this.error = null;
  }
  login(form: any) {
    this.loading = true;
    this.student = form.value;
    this.auhthservice.login(this.student).subscribe(
      (res) => {
        this.studentdetails = res;

        var token = this.encservice.encrypt(this.studentdetails.access_token);

        localStorage.setItem(
          'accesstocken',

          token
        );
        localStorage.setItem(
          'student',
          this.encservice.encrypt(JSON.stringify(this.studentdetails.student))
        );
        localStorage.setItem(
          'hasapplied',
          this.encservice.encrypt(
            JSON.stringify(this.studentdetails.hasapplied)
          )
        );
        console.log(res);

        this.loading = false;

        this.router.navigate(['student/home']);
      },
      (error) => {
        this.error = error.error.message;
        console.log(error);
        this.loading = false;
      }
    );
  }
}
