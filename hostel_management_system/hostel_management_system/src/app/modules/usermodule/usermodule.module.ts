import { RoomService } from './../../services/room.service';
import { HostelService } from './../../services/hostel.service';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UsermoduleRoutingModule } from './usermodule-routing.module';
import { UserdashboardComponent } from './componets/userdashboard/userdashboard.component';
import { NavbarComponent } from './componets/navbar/navbar.component';
import { FooterComponent } from './componets/footer/footer.component';
import { SidenavbarComponent } from './componets/sidenavbar/sidenavbar.component';
import { HostelsComponent } from './componets/hostels/hostels.component';
import { HomeComponent } from './componets/home/home.component';
import { HostelPreferenceComponent } from './componets/hostel-preference/hostel-preference.component';
import { RoomsettingsComponent } from './componets/roomsettings/roomsettings.component';
import { DataTablesModule } from  'angular-datatables';
import { HttpClientModule } from '@angular/common/http';
import { RoomsComponent } from './componets/rooms/rooms.component';
import { FormsModule } from '@angular/forms';

@NgModule({
  declarations: [
    UserdashboardComponent,
    NavbarComponent,
    FooterComponent,
    SidenavbarComponent,
    HostelsComponent,
    HomeComponent,
    HostelPreferenceComponent,
    RoomsettingsComponent,
    RoomsComponent,
  ],
  imports: [
    CommonModule,
    UsermoduleRoutingModule,
    DataTablesModule,
    HttpClientModule,
    FormsModule


  ], providers:[HostelService,RoomService],
   exports: [FooterComponent]
})
export class UsermoduleModule { }
