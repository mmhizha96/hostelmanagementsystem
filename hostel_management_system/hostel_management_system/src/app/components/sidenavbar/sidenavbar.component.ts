import { AuthService } from './../../services/auth.service';
import { Component, OnInit } from '@angular/core';
import { OnSameUrlNavigation, Router } from '@angular/router';

@Component({
  selector: 'app-sidenavbar',
  templateUrl: './sidenavbar.component.html',
  styleUrls: ['./sidenavbar.component.css']
})
export class SidenavbarComponent implements OnInit {
  authenticated:any;
  role:any;
  userrole:any;
  constructor(private authservice:AuthService,private router:Router){
    this.router.events.subscribe((event) => {

      // console.log("change detected");

      this.authenticated=this.authservice.authenticated();
      this.role=this.authservice.getRole();
      this.userrole=this.authservice.getRole();

    });
  }

  ngOnInit(): void {


  }



}
