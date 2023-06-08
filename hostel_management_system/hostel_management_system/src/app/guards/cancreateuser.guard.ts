import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import { ParamsService } from '../services/params.service';

@Injectable({
  providedIn: 'root'
})
export class CancreateuserGuard implements CanActivate {
cancreateuser:boolean=false;

constructor(private params:ParamsService,private router:Router)
{}
  canActivate()
    {
      if(this.getright()){
        return true;
      }else{
this.router.navigate(['admin/hostels']);
        return false;
      }

  }

  getright(){
var myrights=this.params.getparam('myrights');
this.cancreateuser=myrights.create_users;
return this.cancreateuser;
  }

}
