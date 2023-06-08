import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment.development';

@Injectable({
  providedIn: 'root'
})

export class RoomallocationService {
serverip=environment.domain;
  constructor(
private httpclient:HttpClient,

  ) { }


approve_reject(data:any,headers:any){
  return this.httpclient.post(this.serverip+"/approve_reject",data,headers);
}
  getroomstoallocate(data:any,headers:any){
    console.log(data);

return this.httpclient.post(this.serverip+"/getroomstoallocate",data,headers);
  }
  allocateRooms(data:any,headers:any){
    return this.httpclient.post(this.serverip+"/roomallocation",data,headers);
  }
  getRoomallocation(data:any,headers:any){
    return this.httpclient.post(this.serverip+"/getroomallocation",data,headers);
  }
}
