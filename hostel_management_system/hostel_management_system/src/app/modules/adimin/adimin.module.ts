import { RoomService } from './../../services/room.service';
import { FloorService } from './../../services/floor.service';
import { HostelService } from 'src/app/services/hostel.service';
import { LocationService } from './../../services/location.service';
import { NgModule } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';

import { AdiminRoutingModule } from './adimin-routing.module';
import { AdmindashboardComponent } from './components/admindashboard/admindashboard.component';
import { UsermoduleModule } from '../usermodule/usermodule.module';


import { FormsModule } from '@angular/forms';

import { CreateactiveperiodComponent } from './components/createactiveperiod/createactiveperiod.component';
import { ActiveperiodsService } from 'src/app/services/activeperiods.service';
import { PeriodsService } from 'src/app/services/periods.service';
import { LevelsService } from 'src/app/services/levels.service';

import { DataTablesModule } from 'angular-datatables';
import { BatchesComponent } from './components/batches/batches.component';
import { YearserviceService } from 'src/app/services/yearservice.service';

import { BatchesService } from 'src/app/services/batches.service';
import { PeriodsComponent } from './components/periods/periods.component';

import { HostelpreferenceComponent } from './components/hostelpreference/hostelpreference.component';

import { HostelsComponent } from './components/hostels/hostels.component';
import { FloorsComponent } from './components/floors/floors.component';
import { RoomsComponent } from './components/rooms/rooms.component';
import { RoomstypesComponent } from './components/roomstypes/roomstypes.component';
import { ResavationsComponent } from './components/resavations/resavations.component';
import { RoomcostsComponent } from './components/roomcosts/roomcosts.component';
import { RoomallocationComponent } from './components/roomallocation/roomallocation.component';
import { RoomallocationService } from 'src/app/services/roomallocation.service';
import { UsersComponent } from './components/users/users.component';
import { UserRightsComponent } from './components/user-rights/user-rights.component';
import { ViewBatchComponent } from './components/view-batch/view-batch.component';



@NgModule({
  declarations: [
    AdmindashboardComponent,

    CreateactiveperiodComponent,
PeriodsComponent,
    BatchesComponent,


    HostelpreferenceComponent,

    HostelsComponent,
     FloorsComponent,
     RoomsComponent,
     RoomstypesComponent,
     ResavationsComponent,
     RoomcostsComponent,
     RoomallocationComponent,
     UsersComponent,
     UserRightsComponent,
     ViewBatchComponent,
  ],
  imports: [
    FormsModule,
    CommonModule,
    AdiminRoutingModule,
    UsermoduleModule,
    DataTablesModule,
  ],
  providers: [
    LocationService,
    HostelService,
    FloorService,
    RoomService,
    ActiveperiodsService,
    PeriodsService,
    LevelsService,
    YearserviceService,
    DatePipe,
    BatchesService,
    RoomallocationService
  ],
})
export class AdiminModule {}
