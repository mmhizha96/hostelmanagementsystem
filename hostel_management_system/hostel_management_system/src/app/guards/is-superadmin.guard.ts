import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class IsSuperadminGuard implements CanActivate {
  constructor(private authservice:AuthService,private router:Router){

  }
  canActivate()
  {
    if(this.authservice.getRole()==1){
      return true;
    }else{
this.router.navigate(['admin/hostels']);
      return false;
    }

  }

}
