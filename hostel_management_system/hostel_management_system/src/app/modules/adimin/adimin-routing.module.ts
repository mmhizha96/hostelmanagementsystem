import { IsSuperadminGuard } from './../../guards/is-superadmin.guard';
import { BatchesComponent } from './components/batches/batches.component';
import { PeriodsComponent } from './components/periods/periods.component';
import { CreateactiveperiodComponent } from './components/createactiveperiod/createactiveperiod.component';

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from '../usermodule/componets/home/home.component';

import { RoomsettingsComponent } from '../usermodule/componets/roomsettings/roomsettings.component';
import { AdmindashboardComponent } from './components/admindashboard/admindashboard.component';

import { HostelsComponent } from './components/hostels/hostels.component';
import { RoomsComponent } from './components/rooms/rooms.component';
import { FloorsComponent } from './components/floors/floors.component';
import { ResavationsComponent } from './components/resavations/resavations.component';
import { RoomstypesComponent } from './components/roomstypes/roomstypes.component';
import { RoomcostsComponent } from './components/roomcosts/roomcosts.component';

import { HostelpreferenceComponent } from './components/hostelpreference/hostelpreference.component';
import { RoomallocationComponent } from './components/roomallocation/roomallocation.component';
import { UsersComponent } from './components/users/users.component';
import { UserRightsComponent } from './components/user-rights/user-rights.component';
import { ViewBatchComponent } from './components/view-batch/view-batch.component';
import { CansetroomcostGuard } from 'src/app/guards/cansetroomcost.guard';
import { CancreateuserGuard } from 'src/app/guards/cancreateuser.guard';
import { BlacklistComponent } from './components/blacklist/blacklist.component';

const routes: Routes = [
  {
    path: '',
    component: AdmindashboardComponent,
    children: [
      { path: 'home', component: HomeComponent },
      { path: 'hostels', component: HostelsComponent },
      { path: 'hostelpreference', component: HostelpreferenceComponent },

      { path: 'roomsettings', component: RoomsettingsComponent },
      { path: 'allrooms', component: RoomsComponent },

      { path: 'batches', component: BatchesComponent },

      { path: 'viewbatch', component: ViewBatchComponent },

      { path: 'createactiveperiods', component: CreateactiveperiodComponent },
      { path: 'Allperiods', component: PeriodsComponent },
      { path: 'rights', component: UserRightsComponent },
      { path: 'Resevations', component: ResavationsComponent },
      { path: 'floors', component: FloorsComponent },

      {
        path: 'roomtypes',
        component: RoomstypesComponent,
        canActivate: [IsSuperadminGuard],
      },
      { path: 'roomallocation', component: RoomallocationComponent },
      {
        path: 'roomcosts',
        component: RoomcostsComponent,
        canActivate: [CansetroomcostGuard],
      },
      {
path: 'blacklist',component :BlacklistComponent
      },
      {
        path: 'user',
        component: UsersComponent,
        canActivate: [CancreateuserGuard],
      },
      { path: '', redirectTo: 'admin/home', pathMatch: 'full' },
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AdiminRoutingModule {}
