import { Injectable } from '@angular/core';
import {
  ActivatedRouteSnapshot,
  CanActivate,
  Router,
  RouterStateSnapshot,
  UrlTree,
} from '@angular/router';
import { Observable } from 'rxjs';
import { ParamsService } from '../services/params.service';

@Injectable({
  providedIn: 'root',
})
export class CansetroomcostGuard implements CanActivate {
  cansetroomcost: boolean = false;

  constructor(private params: ParamsService, private router: Router) {}
  canActivate() {
    if (this.getright()) {
      return true;
    } else {
      this.router.navigate(['admin/hostels']);
      return false;
    }
  }

  getright() {
    var myrights = this.params.getparam('myrights');
    this.cansetroomcost = myrights.create_room_costs;
    return this.cansetroomcost;
  }
}
