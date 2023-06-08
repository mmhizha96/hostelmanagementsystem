import { Component, OnInit } from '@angular/core';
import { ParamsService } from 'src/app/services/params.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
  username:any;
  user:any;
  ngOnInit(): void {
    this.user=this.params.getparam('user');
    this.username=this.user.user_details.name;
    console.log(this.user);

  }

constructor(private params:ParamsService){}



}
