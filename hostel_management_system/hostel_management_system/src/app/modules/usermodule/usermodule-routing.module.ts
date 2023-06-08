import { RoomsComponent } from './componets/rooms/rooms.component';
import { HostelPreferenceComponent } from './componets/hostel-preference/hostel-preference.component';
import { HomeComponent } from './componets/home/home.component';
import { HostelsComponent } from './componets/hostels/hostels.component';
import { UserdashboardComponent } from './componets/userdashboard/userdashboard.component';
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { RoomsettingsComponent } from './componets/roomsettings/roomsettings.component';

const routes: Routes = [
  {path:'',component:UserdashboardComponent, children:[
    {path:'home',component:HomeComponent },
    {path:'hostels',component:HostelsComponent },
    {path:'hostelpreference',component:HostelPreferenceComponent },
    {path:'roomsettings',component:RoomsettingsComponent},
    {path:'allrooms',component:RoomsComponent },
    {path:'',redirectTo:'/user/home', pathMatch:'full'}

  ] }];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UsermoduleRoutingModule { }
