import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class IsPasswordvalidGuard implements CanActivate {
  constructor(private authservice:AuthService,private router:Router){}
  canActivate(){
    if(this.authservice.chengePassword()!=1){
      return true;
    }else{
this.router.navigate(['login']);
      return false;

    }
  }

}
