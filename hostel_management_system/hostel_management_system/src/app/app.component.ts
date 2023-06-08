import { Component, OnChanges, OnInit, SimpleChanges } from '@angular/core';
import {
  NavigationEnd,
  NavigationError,
  NavigationStart,
  Router,
} from '@angular/router';
import { AuthService } from './services/auth.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit {
  title = 'hostel_management_system';
  authenticated: any;
  currentRoute: any;
  isVisible = false;
  tohide: any = <HTMLElement>document.getElementById('mysidebar');

  constructor(private authservice: AuthService, private router: Router) {
    this.router.events.subscribe((event) => {
      if (
        !this.authservice.authenticated() || (this.authservice.chengePassword()==1)

      ) {
        this.isVisible = false;


      } else {
        this.isVisible = true;
      }
    });
  }
  ngOnInit(): void {
    this.tohide.style.display = 'none';
  }
}
