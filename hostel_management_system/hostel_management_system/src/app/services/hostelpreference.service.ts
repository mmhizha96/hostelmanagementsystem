import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment.development';

@Injectable({
  providedIn: 'root'
})
export class HostelpreferenceService {
serverIP=environment.domain;
  constructor(private httpclient:HttpClient) { }

getHostelsToAdd(data:any,headers:any){
 return  this.httpclient.post(this.serverIP+'/gethosteltoadd',data,headers);
}
sethostelpreference(data:any,headers:any){
  return  this.httpclient.post(this.serverIP+'/sethostelpreference',data,headers);
 }
 deletehostelpreference(data:any,headers:any){
  return  this.httpclient.post(this.serverIP+'/deletehostelpreference',data,headers);
 }
 gethostelpreference(data:any,headers:any){
  return  this.httpclient.post(this.serverIP+'/gethostelpreference',data,headers);
 }


}
