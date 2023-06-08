import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment.development';

@Injectable({
  providedIn: 'root'
})
export class RoompreferenceService {
serverIp=environment.domain;
  constructor(private httpclient:HttpClient) { }

getRoomsToadd(data:any,headers:any){
 return  this.httpclient.post(this.serverIp+"/getroomstoadd",data,headers);

}
setroompreference(data:any,headers:any){
  return this.httpclient.post(this.serverIp+"/setroompreference",data,headers)
}

getroompreference(data:any,headers:any)
{
  return this.httpclient.post(this.serverIp+"/getroompreference",data,headers)
}
deleteroompreference(data:any,headers:any)
{
  return this.httpclient.post(this.serverIp+"/deleteroompreference",data,headers)
}

}
