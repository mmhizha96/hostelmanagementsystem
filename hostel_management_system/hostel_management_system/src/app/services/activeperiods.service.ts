import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment.development';


@Injectable({
  providedIn: 'root'
})

export class ActiveperiodsService {
  svaerIp=environment.domain;


  constructor(private http:HttpClient) { }

createActiveperiod(data:any,headers:any){

  return this.http.post('http://localhost:8000/api/createactiveperiod',data,headers);

}
getActivePeriod(headers:any)
{
  return this.http.get('http://localhost:8000/api/getactiveperiod',headers);
}

getCurrentActivePeriod(headers:any)
{
  return this.http.get('http://localhost:8000/api/getcurrentactiveperiod',headers);
}


deactivateActiveperiod(data:any,headers:any){

  return this.http.post('http://localhost:8000/api/deactivatePeriod',data,headers);

}
activateActiveperiod(data:any,headers:any){

  return this.http.post('http://localhost:8000/api/activatePeriod',data,headers);

}
deleteActiveperiod(data:any, headers:any){

  return this.http.post('http://localhost:8000/api/deletePeriod',data,headers);

}


updateActtivePeriod(data:any,headers:any){
  return this.http.post('http://localhost:8000/api/updateactiveperiod',data,headers);
}
}
