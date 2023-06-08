import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from 'src/environments/environment.development';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class HostelService {
serverIp=environment.domain;

  constructor(private httpClient:HttpClient) {

  }
deleteHostel(data:any,headers:any){

    return this.httpClient.post(this.serverIp+'/deleteHostel', data,headers);
  }
 updateHostel(data:any,headers:any){
  return this.httpClient.post(this.serverIp+'/updatehostel', data,headers);
  }
findHostels(location:any,headers:any){
  return this.httpClient.post(this.serverIp+'/findhostels',location,headers);
}
getHostels(headers:any)
{

  return this.httpClient.get(this.serverIp+'/hostellist',headers);
}

addHostels(data:any,headers:any)
{
  return this.httpClient.post(this.serverIp+'/addhostel', data,headers);
}

}
