import { TestBed } from '@angular/core/testing';

import { YearserviceService } from './yearservice.service';

describe('YearserviceService', () => {
  let service: YearserviceService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(YearserviceService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
