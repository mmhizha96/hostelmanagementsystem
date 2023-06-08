import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { AuthService } from 'src/app/services/auth.service';


@Component({
  selector: 'app-changepassword',
  templateUrl: './changepassword.component.html',
  styleUrls: ['./changepassword.component.css']
})
export class ChangepasswordComponent implements OnInit {
error:any;
success:any;

  constructor(private authservice:AuthService,private router:Router){}
  ngOnInit(): void {
  this.error=null;
  this.success=null;
  }

  changePassword(form:any){
    this.error=null;
    this.success=null;

    form.value.user_id=this.authservice.getUserId();

if(form.value.password==form.value.c_password){
  this.authservice.changePassword(form.value, {
    headers: this.authservice.getHeaders(),
  }).subscribe(res=>{
    this.success=1;
console.log(res);
this.router.navigate(['login']);

  },error=>{
    console.log(error);
    this.error=error.error.message;

  })
}else{
this.error= "passwords does not match";
}


  }

}
