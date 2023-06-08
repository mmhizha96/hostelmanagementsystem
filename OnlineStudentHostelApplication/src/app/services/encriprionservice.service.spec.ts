import { TestBed } from '@angular/core/testing';

import { EncriprionserviceService } from './encriprionservice.service';

describe('EncriprionserviceService', () => {
  let service: EncriprionserviceService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(EncriprionserviceService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
